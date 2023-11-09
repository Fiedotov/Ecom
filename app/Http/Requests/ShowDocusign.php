<?php

namespace App\Http\Requests;

use App\Models\PaymentSubmission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class ShowDocusign extends FormRequest
{
    private string $requestUrl = 'https://dwrs.omnitoria.io/webhook/f96245c2-c215-42a4-8f18-cde4bd58e68b?id=%s';
    private ?string $url;

    public function rules(): array
    {
        return ['submission_id' => [Rule::exists('payment_submissions', 'id')]];
    }

    public function validationData(): array
    {
        return array_merge(parent::validationData(), ['submission_id' => $this->submission()->id ?? null]);
    }

    public function docusignUrl(): array
    {
        if (! isset($this->url)) {
            $this->url = $this->getUrl();
        }

        return ['url' => $this->url];
    }

    public function responseCode(): int
    {
        return $this->url ? 200 : 400;
    }

    private function getUrl(): ?string
    {
        $submission = $this->submission();

        return $submission->sf_contract_response ?
            Http::get(sprintf($this->requestUrl, data_get($submission, 'sf_contract_response.id')))->json('url') :
            null;
    }

    private function submission(): ?PaymentSubmission
    {
        return $this->route('submission');
    }
}
