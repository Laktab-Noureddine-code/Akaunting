<?php
namespace App\Traits;
use App\Scopes\Company;
trait Tenants
{
    protected static function bootTenants()
    {
        static::addGlobalScope(new Company);
    }
    public function isTenantable()
    {
        $tenantable = $this->tenantable ?? true;
        return ($tenantable === true) && in_array('company_id', $this->getFillable());
    }
    public function isNotTenantable()
    {
        return !$this->isTenantable();
    }
}
