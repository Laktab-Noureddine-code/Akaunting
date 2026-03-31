<?php
namespace App\Events\Document;
use Illuminate\Queue\SerializesModels;
class DocumentTemplates
{
    use SerializesModels;
    public $type;
    public $templates;
    public function __construct($type, $templates)
    {
        $this->type = $type;
        $this->templates = $templates;
    }
}
