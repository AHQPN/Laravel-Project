<?php

namespace App\Http\Services\TaiXe;

use App\Models\BaocaoSuco;
use Illuminate\Support\Facades\DB;

class BaoCaoService
{
    /**
     * Create a new incident report with transaction.
     *
     * @param array $data
     * @param string $manv
     * @return BaocaoSuco
     * @throws \Exception
     */
    public function createBaoCao(array $data, string $manv): BaocaoSuco
    {
        return DB::transaction(function () use ($data, $manv) {
            $data['manv'] = $manv;
            $data['mabaocao'] = $this->generateMaBaoCao();
            $data['trangthai'] = 'Đang xử lý';

            return BaocaoSuco::create($data);
        });
    }

    /**
     * Generate unique ma bao cao.
     *
     * @return string
     */
    private function generateMaBaoCao(): string
    {
        do {
            $ma = 'BC' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (BaocaoSuco::where('mabaocao', $ma)->exists());

        return $ma;
    }
}
