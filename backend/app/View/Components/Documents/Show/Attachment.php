<?php
namespace App\View\Components\Documents\Show;
use App\Abstracts\View\Components\Documents\Show as Component;
class Attachment extends Component
{
    public $transaction_attachment;
    public function render()
    {
        $this->transaction_attachment = collect();
        if (!$this->document->relationLoaded('transactions')) {
            $this->document->load(['transactions.media', 'transactions']);
        }
        if ($this->document->transactions->count()) {
            foreach ($this->document->transactions as $transaction) {
                if (! $transaction->attachment) {
                    continue;
                }
                foreach ($transaction->attachment as $file) {
                    $this->transaction_attachment->push($file);
                }
            }
        }
        return view('components.documents.show.attachment');
    }
}
