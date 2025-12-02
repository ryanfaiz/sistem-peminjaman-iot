<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loan_date',
        'due_date',
        'status',
        'approved_by',
        'approved_at',
        'returned_at',
        'purpose',
        'consent',
        'admin_notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'approved_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'loan_items', 'loan_id', 'item_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function getIsOverdueAttribute(): bool
    {
        // Status 'dipinjam' diimplementasikan sebagai 'APPROVED' yang belum dikembalikan.
        // Peminjaman dianggap 'Overdue' jika statusnya APPROVED/DIPINJAM dan tanggal jatuh tempo sudah lewat.
        return ($this->status === 'disetujui' || $this->status === 'dipinjam')
               && $this->due_date->isPast() 
               && is_null($this->returned_at);
    }
}