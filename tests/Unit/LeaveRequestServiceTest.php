<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\LeaveRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveRequestForApproval;
use App\Mail\LeaveRequestRejected;
use Database\Factories\DepartmentFactory;
use Database\Factories\LeaveRequestFactory;
use Database\Factories\LeaveTypeFactory;
use Database\Factories\PositionFactory;
use Database\Factories\UserFactory;

class LeaveRequestServiceTest extends TestCase
{
    use RefreshDatabase;
    
    protected $leaveRequestService;
    
    public function setUp(): void
    {
        parent::setUp();
        
        // Create actual service instance
        $this->leaveRequestService = new LeaveRequestService();
        
        // Disable actual mail sending
        Mail::fake();
    }
    
    /**
     * Test finding direct manager.
     */
    public function test_find_direct_manager()
    {
        // Create a department
        $department = DepartmentFactory::new()->create();
        
        // Create a manager position
        $managerPosition = PositionFactory::new()->create(['title' => 'Manager']);
        
        // Create a manager user
        $manager = UserFactory::new()->create(['name' => 'John Manager']);
        
        // Associate manager with department
        $manager->userDepartmentPositions()->create([
            'department_id' => $department->id,
            'position_id' => $managerPosition->id,
            'is_manager' => true,
        ]);
        
        // Create a regular employee
        $employee = UserFactory::new()->create();
        
        // Find direct manager
        $foundManager = $this->leaveRequestService->findDirectManager($employee->id, $department->id);
        
        // Assert correct manager was found
        $this->assertInstanceOf(User::class, $foundManager);
        $this->assertEquals($manager->id, $foundManager->id);
        $this->assertEquals('John Manager', $foundManager->name);
    }
    
    /**
     * Test finding direct manager when no manager exists.
     */
    public function test_find_direct_manager_none_exists()
    {
        // Create a department with no manager
        $department = DepartmentFactory::new()->create();
        
        // Create a regular employee
        $employee = UserFactory::new()->create();
        
        // Find direct manager
        $foundManager = $this->leaveRequestService->findDirectManager($employee->id, $department->id);
        
        // Assert no manager was found
        $this->assertNull($foundManager);
    }
    
    /**
     * Test finding HR managers.
     */
    public function test_find_hr_managers()
    {
        // Create HR position
        $hrPosition = PositionFactory::new()->create(['title' => 'HR Manager']);
        
        // Create department
        $department = DepartmentFactory::new()->create(['name' => 'Human Resources']);
        
        // Create HR managers
        $hrManager1 = UserFactory::new()->create(['name' => 'HR Manager 1']);
        $hrManager2 = UserFactory::new()->create(['name' => 'HR Manager 2']);
        
        // Associate with HR position
        $hrManager1->userDepartmentPositions()->create([
            'department_id' => $department->id,
            'position_id' => $hrPosition->id,
            'is_manager' => true,
        ]);
        
        $hrManager2->userDepartmentPositions()->create([
            'department_id' => $department->id,
            'position_id' => $hrPosition->id,
            'is_manager' => true,
        ]);
        
        // Create regular manager (not HR)
        $regularManager = UserFactory::new()->create();
        $regularPosition = PositionFactory::new()->create(['title' => 'Department Manager']);
        
        $regularManager->userDepartmentPositions()->create([
            'department_id' => DepartmentFactory::new()->create()->id,
            'position_id' => $regularPosition->id,
            'is_manager' => true,
        ]);
        
        // Find HR managers
        $hrManagers = $this->leaveRequestService->findHrManagers();
        
        // Assert correct managers found
        $this->assertCount(2, $hrManagers);
        $this->assertTrue($hrManagers->contains('id', $hrManager1->id));
        $this->assertTrue($hrManagers->contains('id', $hrManager2->id));
        $this->assertFalse($hrManagers->contains('id', $regularManager->id));
    }
    
    /**
     * Test notifying manager of leave request.
     */
    public function test_notify_manager()
    {
        // Create manager
        $manager = UserFactory::new()->create(['email' => 'wonic89166@naobk.com']);
        
        // Create leave request
        $leaveRequest = $this->createLeaveRequest();
        
        // Notify manager
        $this->leaveRequestService->notifyManager($leaveRequest, $manager);
        
        // Assert email was sent
        Mail::assertSent(LeaveRequestForApproval::class, function ($mail) use ($manager) {
            return $mail->hasTo($manager->email);
        });
    }
    
    /**
     * Test notifying HR managers.
     */
    public function test_notify_hr_managers()
    {
        // Create HR position
        $hrPosition = PositionFactory::new()->create(['title' => 'HR Manager']);
        
        // Create department
        $department = DepartmentFactory::new()->create(['name' => 'Human Resources']);
        
        // Create HR managers
        $hrManager1 = UserFactory::new()->create(['email' => 'hr1@example.com']);
        $hrManager2 = UserFactory::new()->create(['email' => 'hr2@example.com']);
        
        // Associate with HR position
        $hrManager1->userDepartmentPositions()->create([
            'department_id' => $department->id,
            'position_id' => $hrPosition->id,
            'is_manager' => true,
        ]);
        
        $hrManager2->userDepartmentPositions()->create([
            'department_id' => $department->id,
            'position_id' => $hrPosition->id,
            'is_manager' => true,
        ]);
        
        // Mock findHrManagers to return our test HR managers
        $mockService = $this->partialMock(LeaveRequestService::class, function ($mock) use ($hrManager1, $hrManager2) {
            $mock->shouldReceive('findHrManagers')
                ->once()
                ->andReturn(collect([$hrManager1, $hrManager2]));
        });
        
        // Create leave request
        $leaveRequest = $this->createLeaveRequest();
        
        // Notify HR managers
        $mockService->notifyHrManagers($leaveRequest);
        
        // Assert emails were sent to both HR managers
        Mail::assertSent(LeaveRequestForApproval::class, function ($mail) use ($hrManager1) {
            return $mail->hasTo($hrManager1->email);
        });
        
        Mail::assertSent(LeaveRequestForApproval::class, function ($mail) use ($hrManager2) {
            return $mail->hasTo($hrManager2->email);
        });
        
        // Assert email was sent twice (once to each HR manager)
        Mail::assertSent(LeaveRequestForApproval::class, 2);
    }
    
    /**
     * Test notifying employee of rejection.
     */
    public function test_notify_rejection()
    {
        // Create leave request
        $leaveRequest = $this->createLeaveRequest();
        $leaveRequest->manager_comment = 'Cannot approve due to upcoming deadline';
        
        // Notify of rejection
        $this->leaveRequestService->notifyRejection($leaveRequest);
        
        // Assert email was sent to employee
        Mail::assertSent(LeaveRequestRejected::class, function ($mail) use ($leaveRequest) {
            return $mail->hasTo($leaveRequest->user->email);
        });
    }
    
    /**
     * Helper to create a leave request.
     */
    private function createLeaveRequest()
    {
        $user = UserFactory::new()->create();
        $department = DepartmentFactory::new()->create();
        $leaveType = LeaveTypeFactory::new()->create();
        
        return LeaveRequestFactory::new()->create([
            'user_id' => $user->id,
            'department_id' => $department->id,
            'leave_type_id' => $leaveType->id,
            'status' => 'pending',
        ]);
    }
}