<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Models\Organization;
use App\Models\OrganizationLicense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SystemAdminLicenseController extends Controller
{

    public function index($id)
    {
        return view('systemAdmin.organization.license.all')
            ->with('organization', Organization::find($id))
            ->with('licenses', OrganizationLicense::where('organization_id', $id)->get());
    }

    public function activeLicense($organizationId, $licenseId)
    {
        $organization = Organization::find($organizationId);

        if (!$organization) {
            return response()->json([
                'status' => false,
                'message' => 'Kurum Bulunamadı Aktifleştirme Başarısız'
            ]);
        }

        $license = OrganizationLicense::find($licenseId);

        if (!$license) {
            return response()->json([
                'status' => false,
                'message' => 'Lisans Bulunamadı Aktifleştirme Başarısız'
            ]);
        }
        if ($organization->id != $license->organization_id) {
            return response()->json(['status' => false, 'message' => 'Lisans bu kuruluşa ait değil']);
        }
        OrganizationLicense::where('organization_id', $organization->id)->update(["active" => false]);
        $license->active = true;

        if ($license->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Lisans Aktifleştirme Başarılı'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Lisans Aktifleştirme Başarısız'
            ]);
        }
    }
    public function create($id)
    {
        return view('systemAdmin.organization.license.create')
            ->with('organization', Organization::find($id));
    }


    public function store(Request $request, $organizationId)
    {
        $organization = Organization::find($organizationId);

        if (!$organization) {
            return response()->json([
                'status' => false,
                'message' => 'Kurum Bulunamadı Lisans Ekleme İşlemi Başarısız'
            ]);
        }
        try {
            if ($request->input('active') == "on") {
                OrganizationLicense::where('organization_id', $organization->id)->update(["active" => false]);
            }
            $license = new OrganizationLicense;
            $license->end_date = Carbon::createFromFormat('d/m/Y', $request->input('licenseExpireDate'))->format('Y-m-d');
            $license->start_date = Carbon::createFromFormat('d/m/Y', $request->input('licenseStartDate'))->format('Y-m-d');
            $license->active = ($request->input('active') == "on" ? true : false);
            $license->organization_id = $organizationId;
            if ($license->saveOrFail()) {
                return response()->json([
                    "status" => true,
                    "message" => "Ekleme İşlemi Başarılı",
                    "url" => route('systemAdmin.organization.license.index', ["organization" => $organization->id])
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Ekleme İşlemi Başarısız",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrganizationLicense  $organizationLicenses
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrganizationLicense  $organizationLicenses
     */
    public function edit($organizationId, $licenseId)
    {
        return view('systemAdmin.organization.license.edit')
            ->with('organization', Organization::find($organizationId))
            ->with('license', OrganizationLicense::find($licenseId));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request, $organizationId, $licenseId)
    {
        $organization = Organization::find($organizationId);

        if (!$organization) {
            return response()->json([
                'status' => false,
                'message' => 'Kurum Bulunamadı Lisans Güncelleme İşlemi Başarısız'
            ]);
        }
        $license = OrganizationLicense::find($licenseId);

        if (!$license) {
            return response()->json([
                'status' => false,
                'message' => 'Lisans Bulunamadı Güncelleme Başarısız'
            ]);
        }
        if ($organization->id != $license->organization_id) {
            return response()->json(['status' => false, 'message' => 'Lisans bu kuruluşa ait değil']);
        }
        try {
            if ($request->input('active') == "on") {
                OrganizationLicense::where('organization_id', $organization->id)->update(["active" => false]);
            }
            $license->end_date = Carbon::createFromFormat('d/m/Y', $request->input('licenseExpireDate'))->format('Y-m-d');
            $license->start_date = Carbon::createFromFormat('d/m/Y', $request->input('licenseStartDate'))->format('Y-m-d');
            $license->active = ($request->input('active') == "on" ? true : false);
            if ($license->saveOrFail()) {
                return response()->json([
                    "status" => true,
                    "message" => "Güncelleme İşlemi Başarılı",
                    "url" => route('systemAdmin.organization.license.index', ["organization" => $organization->id])
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Güncelleme İşlemi Başarısız",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }


    public function destroy($organizationId, $licenseId)
    {
        $organization = Organization::find($organizationId);

        if (!$organization) {
            return response()->json([
                'status' => false,
                'message' => 'Kurum Bulunamadı Aktifleştirme Başarısız'
            ]);
        }

        $license = OrganizationLicense::find($licenseId);

        if (!$license) {
            return response()->json([
                'status' => false,
                'message' => 'Lisans Bulunamadı Aktifleştirme Başarısız'
            ]);
        }
        if ($organization->id != $license->organization_id) {
            return response()->json(['status' => false, 'message' => 'Lisans bu kuruluşa ait değil']);
        }

        if ($license->delete()) {
            if ($license->active) {
                $lastLicense = OrganizationLicense::where('organization_id', $organization->id)->first();
                if ($lastLicense) {
                    $lastLicense->active = true;
                    $lastLicense->save();
                }
            }
            return  response()->json([
                'status' => true,
                'message' => 'Lisans Silme İşlemi Başarılı'
            ]);
        } else {
            return  response()->json([
                'status' => false,
                'message' => 'Lisans Silme İşlemi Başarısız'
            ]);
        }
    }
}
