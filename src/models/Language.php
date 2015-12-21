<?php 

namespace AbbyLynn\Translatable\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model {

  /**
     * The table associated with the model.
     *
     * @var string
     */
  protected $table = 'languages';

  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
  protected $fillable = ['name', 'native', 'abbr', 'active', 'default'];

  /**
     * Scope a query to only include active languages.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

  /**
     * Find specific language based on abbreviation.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAbbr($query, $abbr)
    {
        return $query->where('abbr', $abbr);
    }

}