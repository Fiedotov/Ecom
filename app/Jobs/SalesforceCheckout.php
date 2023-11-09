<?php

namespace App\Jobs;

use App\Models\PaymentSubmission;
use App\SubmitTransaction\Pipes\CreateSalesforceAccount;
use App\SubmitTransaction\Pipes\CreateSalesforceContact;
use App\SubmitTransaction\Pipes\CreateSalesforceContract;
use App\SubmitTransaction\Pipes\UpdateSalesforceProperty;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SalesforceCheckout implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private PaymentSubmission $submission;

    public function __construct(PaymentSubmission $submission)
    {
        $this->submission = $submission;
    }

    public function handle(): void
    {
        $this->pipeline()
            ->send($this->submission)
            ->through([
                CreateSalesforceAccount::class,
                CreateSalesforceContact::class,
                UpdateSalesforceProperty::class,
                CreateSalesforceContract::class,
            ])
            ->then(function () {
                $this->submission->update(['completed_at' => now()]);
                dispatch(new SyncSalesforceData($this->submission->getUser()));
            });
    }

    private function pipeline(): Pipeline
    {
        return app(Pipeline::class);
    }
}
