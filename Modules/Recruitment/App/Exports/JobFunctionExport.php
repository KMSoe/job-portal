<?php
namespace Modules\Recruitment\App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class JobFunctionExport implements FromView, ShouldAutoSize
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function view(): View
    {
        $items = $this->items;

        return view('recruitment::exports.job_function_export', compact('items'));
    }
}
