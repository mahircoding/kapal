<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'user_id',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'religion',
        'npwp_path',
        'address_ktp',
        'domicile',
        'ktp_path',
        'kk_path',
        'cv_path',
        'certificate_path',
        'coe_path',
        'medical_certificate_path',
        'buku_pelaut_path',
        'account_data_path',
        'position',
        'cover_letter',
        'available_interview_date',
        'admin_note',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
