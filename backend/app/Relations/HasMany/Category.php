<?php
namespace App\Relations\HasMany;
use App\Models\Setting\Category as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Category extends HasMany
{
    public function getResults()
    {
        if (! is_null($this->getParentKey()) && $this->related instanceof Model) {
            return $this->query->getWithoutChildren();
        }
        return ! is_null($this->getParentKey())
                ? $this->query->get()
                : $this->related->newCollection();
    }
}
