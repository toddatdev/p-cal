<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Earning extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'earning',
        'year',
        'month',
        'exg_rate',
        'employee_commission',
        'manager_commission',
        'hod_commission',
        'status',
        'is_del',
    ];

    /**
     * @return BelongsTo
     */
    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @param $commissionType
     * @param $commissionTypeExgRate
     * @param $projectID
     * @return false|string
     */
    public function calculateCommissionTotal($commissionType, $commissionTypeExgRate, $projectID): false|string
    {
        $earnings = Earning::where([
            ['project_id', '=', $projectID],
            ['status', '=', 0],
            ['is_del', '=', 0],
        ])->get();

        if ($earnings->isEmpty()) {
            return false;
        }

        $totalCommissionInDollars = $earnings->sum($commissionType);
        $totalCommissionByExgRate = $earnings->sum($commissionTypeExgRate);

        $exgRateCurrency = $earnings[0]->currency;
        $consistentCurrency = true;

        foreach ($earnings as $earning) {
            if ($exgRateCurrency != $earning->currency) {
                $consistentCurrency = false;
                break;
            }
        }

        if (!$consistentCurrency) {
            $exgRateCurrency = '';
        }

        return '$' . number_format($totalCommissionInDollars, 2) . ' / ' . number_format($totalCommissionByExgRate, 2) . ' ' . strtoupper($exgRateCurrency);
    }
}
