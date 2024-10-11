<?php
namespace App\DTOs;

use Illuminate\Http\Request;

class UserData
{
    public $vaccine_center_id;
    public $name;
    public $email;
    public $nid;
    public $mobile;
    public $status;
    public $scheduled_date;

    public function __construct(
        int $vaccine_center_id,
        string $name,
        string $email,
        string $nid,
        string $mobile,
        ?string $status = null,          // Nullable status
        ?string $scheduled_date = null   // Nullable scheduled date
    ) {
        $this->vaccine_center_id = $vaccine_center_id;
        $this->name = $name;
        $this->email = $email;
        $this->nid = $nid;
        $this->mobile = $mobile;
        $this->status = $status ?? 'Not scheduled';  // Provide default value if null
        $this->scheduled_date = $scheduled_date;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->vaccine_center_id,
            $request->name,
            $request->email,
            $request->nid,
            $request->mobile,
            $request->status,
            $request->scheduled_date
        );
    }
}
