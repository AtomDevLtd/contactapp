<?php

namespace App\Models;

use App\Contracts\SyncableWithApiIntegrations;
use App\Models\Concerns\ApiIntegrations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Propaganistas\LaravelPhone\PhoneNumber;

class Contact extends Model implements SyncableWithApiIntegrations
{
    use HasFactory;
    use ApiIntegrations;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    protected $appends = [
        'phone_formatted',
    ];

    /**
     * All of the models Mutators&Accessors
     * should begin from here.
     */

    public function getPhoneFormattedAttribute(): ?string
    {
        if ($this->hasValidPhone()) {
            return PhoneNumber::make(
                $this->phone,
                array_keys(countryCodes())
            )->formatE164();
        }

        return null;
    }

    /**
     * All of the models scopes methods
     * should begin from here.
     */

    /**
     * Scope a query to only include contacts for a specific user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->getKey());
    }

    /**
     * Scope a query to only include contacts for a specific catalog.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Catalog $catalog
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCatalog(Builder $query, Catalog $catalog): Builder
    {
        return $query->where('catalog_id', $catalog->getKey());
    }

    /**
     * All of the models custom methods
     * should begin from here.
     */

    public function hasValidPhone(): bool
    {
        if (blank($this->phone)) {
            return false;
        }

        $phoneNumber = PhoneNumber::make($this->phone, array_keys(countryCodes()));

        try {
            $isFromAllowedCountries = $phoneNumber->isOfCountry(array_keys(countryCodes()));
        } catch (\Exception $exception) {
            return false;
        }

        return filled($this->phone) && $isFromAllowedCountries;
    }

    /**
     * All of the models method overwrites
     * should begin from here.
     */


    /**
     * All of the models relationships
     * should begin from here.
     */

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class);
    }
}
