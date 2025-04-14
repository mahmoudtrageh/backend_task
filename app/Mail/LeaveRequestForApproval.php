<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveRequestForApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The leave request instance.
     *
     * @var \App\Models\LeaveRequest
     */
    public $leaveRequest;

    /**
     * The manager who needs to approve.
     *
     * @var \App\Models\User
     */
    public $manager;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @param  \App\Models\User  $manager
     * @return void
     */
    public function __construct(LeaveRequest $leaveRequest, User $manager)
    {
        $this->leaveRequest = $leaveRequest;
        $this->manager = $manager;
    }

    public function build()
    {
        return $this->view('emails.leave-requests.manager-approval')
                   ->subject('Leave Request Awaiting Your Approval');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
