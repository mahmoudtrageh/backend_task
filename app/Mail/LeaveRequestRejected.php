<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestRejected extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The leave request instance.
     *
     * @var \App\Models\LeaveRequest
     */
    public $leaveRequest;

    /**
     * Who rejected the request (manager or HR).
     *
     * @var string
     */
    public $rejectedBy;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @param  string  $rejectedBy
     * @return void
     */
    public function __construct(LeaveRequest $leaveRequest, $rejectedBy = 'manager')
    {
        $this->leaveRequest = $leaveRequest;
        $this->rejectedBy = $rejectedBy;
    }

    public function build()
    {
        return $this->view('emails.leave-requests.rejected')
                   ->subject('Leave Request Rejected');
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
