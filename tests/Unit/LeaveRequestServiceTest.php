<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\LeaveRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveRequestForApproval;
use App\Mail\LeaveRequestRejected;
use App\Repositories\LeaveRequestRepositoryInterface;
use Database\Factories\DepartmentFactory;
use Database\Factories\LeaveRequestFactory;
use Database\Factories\LeaveTypeFactory;
use Database\Factories\PositionFactory;
use Database\Factories\UserFactory;
use Mockery;

class LeaveRequestServiceTest extends TestCase
{
    use RefreshDatabase;
    
    protected $leaveRequestService;
    protected $mockRepository;
    
    public function setUp(): void
    {
        parent::setUp();
        
        $this->mockRepository = Mockery::mock(LeaveRequestRepositoryInterface::class);
        
        $this->leaveRequestService = new LeaveRequestService($this->mockRepository);
        
        Mail::fake();
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function test_index()
    {
        $request = request();
        
        $this->mockRepository->shouldReceive('index')
            ->once()
            ->with($request)
            ->andReturn([]);
            
        $result = $this->leaveRequestService->index($request);
        
        $this->assertEquals([], $result);
    }

    public function test_create()
    {
        $data = [
            'user_id' => 1,
            'leave_type_id' => 1,
            'department_id' => 1,
            'start_date' => '2025-05-01',
            'end_date' => '2025-05-05',
            'reason' => 'Vacation',
            'status' => 'pending',
            'direct_manager' => 2
        ];
        
        $leaveRequest = LeaveRequestFactory::new()->make($data);
        
        $this->mockRepository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($leaveRequest);
            
        $result = $this->leaveRequestService->create($data);
        
        $this->assertEquals($leaveRequest, $result);
    }
    
    public function test_update()
    {
        $data = [
            'status' => 'approved',
            'manager_comment' => 'Approved'
        ];
        $id = 1;
        
        $this->mockRepository->shouldReceive('update')
            ->once()
            ->with($data, $id)
            ->andReturn(1);
            
        $result = $this->leaveRequestService->update($data, $id);
        
        $this->assertEquals(1, $result);
    }
    
    public function test_find_direct_manager()
    {
        $department = DepartmentFactory::new()->create();
        
        $managerPosition = PositionFactory::new()->create(['title' => 'Manager']);
        
        $manager = UserFactory::new()->create(['name' => 'John Manager']);
        
        $manager->userDepartmentPositions()->create([
            'department_id' => $department->id,
            'position_id' => $managerPosition->id,
            'is_manager' => true,
        ]);
        
        $employee = UserFactory::new()->create();
        
        $foundManager = $this->leaveRequestService->findDirectManager($employee->id, $department->id);
        
        $this->assertInstanceOf(User::class, $foundManager);
        $this->assertEquals($manager->id, $foundManager->id);
        $this->assertEquals('John Manager', $foundManager->name);
    }
    public function test_find_direct_manager_none_exists()
    {
        $department = DepartmentFactory::new()->create();
        
        $employee = UserFactory::new()->create();
        
        $foundManager = $this->leaveRequestService->findDirectManager($employee->id, $department->id);
        
        $this->assertNull($foundManager);
    }
    public function test_find_hr_managers()
    {
        $hrPosition = PositionFactory::new()->create(['title' => 'HR Manager']);
        
        $department = DepartmentFactory::new()->create(['name' => 'Human Resources']);
        
        $hrManager1 = UserFactory::new()->create(['name' => 'HR Manager 1']);
        $hrManager2 = UserFactory::new()->create(['name' => 'HR Manager 2']);
        
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
        
        $regularManager = UserFactory::new()->create();
        $regularPosition = PositionFactory::new()->create(['title' => 'Department Manager']);
        
        $regularManager->userDepartmentPositions()->create([
            'department_id' => DepartmentFactory::new()->create()->id,
            'position_id' => $regularPosition->id,
            'is_manager' => true,
        ]);
        
        $hrManagers = $this->leaveRequestService->findHrManagers();
        
        $this->assertCount(2, $hrManagers);
        $this->assertTrue($hrManagers->contains('id', $hrManager1->id));
        $this->assertTrue($hrManagers->contains('id', $hrManager2->id));
        $this->assertFalse($hrManagers->contains('id', $regularManager->id));
    }
    public function test_notify_manager()
    {
        $manager = UserFactory::new()->create(['email' => 'manager@example.com']);
        
        $leaveRequest = $this->createLeaveRequest();
        
        $this->leaveRequestService->notifyManager($leaveRequest, $manager);
        
        Mail::assertSent(LeaveRequestForApproval::class, function ($mail) use ($manager) {
            return $mail->hasTo($manager->email);
        });
    }
    public function test_notify_hr_managers()
    {
        $hrPosition = PositionFactory::new()->create(['title' => 'HR Manager']);
        
        $department = DepartmentFactory::new()->create(['name' => 'Human Resources']);
        
        $hrManager1 = UserFactory::new()->create(['email' => 'hr1@example.com']);
        $hrManager2 = UserFactory::new()->create(['email' => 'hr2@example.com']);
        
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
        
        $mockService = $this->partialMock(LeaveRequestService::class, function ($mock) use ($hrManager1, $hrManager2) {
            $mock->shouldReceive('findHrManagers')
                ->once()
                ->andReturn(collect([$hrManager1, $hrManager2]));
        });
        
        $leaveRequest = $this->createLeaveRequest();
        
        $mockService->notifyHrManagers($leaveRequest);
        
        Mail::assertSent(LeaveRequestForApproval::class, function ($mail) use ($hrManager1) {
            return $mail->hasTo($hrManager1->email);
        });
        
        Mail::assertSent(LeaveRequestForApproval::class, function ($mail) use ($hrManager2) {
            return $mail->hasTo($hrManager2->email);
        });
        
        Mail::assertSent(LeaveRequestForApproval::class, 2);
    }
    public function test_notify_rejection()
    {
        $leaveRequest = $this->createLeaveRequest();
        $leaveRequest->user->email = 'employee@example.com';
        $leaveRequest->manager_comment = 'Cannot approve due to upcoming deadline';
        
        $this->leaveRequestService->notifyRejection($leaveRequest);
        
        Mail::assertSent(LeaveRequestRejected::class, function ($mail) use ($leaveRequest) {
            return $mail->hasTo($leaveRequest->user->email);
        });
    }
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