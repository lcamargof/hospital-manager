<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Ward;
use App\Bed;
use App\User;
use App\Patient;

class BedController extends Controller {

   /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index() {
      $wards = Ward::with('beds')->get();

      if (Auth::user()->role == 'master') {
         $beds = Bed::with('ward')->get();

         return view('admin.adminlayout')
            ->with('location', 'beds')
            ->nest('content', 'admin.bedsIndex', array(
               'beds' => $beds,
               'wards' => $wards
            ));
      } else {
         $beds = Bed::with('ward')->with('patient')->get();
         $patients = Patient::all();

         return view('reception.receptionlayout')
            ->with('location', 'beds')
            ->nest('content', 'reception.bedsIndex', array(
               'beds' => $beds,
               'wards' => $wards,
               'patients' => $patients
            ));
      }
   }

   /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
   public function store(Request $request) {
      if($request->ajax() && Auth::user()->role == 'master') {
         switch ($request->input('type')) {
            case 'ward':
               // Validating the fields
               $this->validate($request, [
                  'identification' => 'required|unique:wards',
                  'capacity' => 'required|integer'
               ]);

               // Filling the new ward
               $ward = new Ward();
               $ward->identification = $request->input('identification');
               $ward->capacity = $request->input('capacity');
               $ward->save();

               // Json response
               return response()->json([
                  'result' => 'success', 
                  'msg' => '<strong> Success!!!</strong> Ward created successfully.', 
                  'row' => Ward::where('id', $ward->id)->get()
               ]);

            case 'bed':
               $bed = new Bed();
               // Validation if ward
               if($request->input('ward_id')) {
                  $this->validate($request, [
                     'identification'  => 'required|unique:beds',
                     'ward_id'         => 'exists:wards,id'
                  ]);

                  // Get ward
                  $ward = Ward::where('id', $request->input('ward_id'))->with('beds')->get()->first();
                  
                  // Check if there are space
                  if(!$ward->capacity > count($ward->beds)) {
                     // No capacity
                     return response()->json([
                        'result' => 'error', 
                        'msg' => '<strong> Error!!!</strong> Ward is at max capacity.'
                     ]);                     
                  }

                  // Assign ward
                  $bed->ward_id = $request->input('ward_id');
               } else {
                  // Validation if there isn't a ward
                  $this->validate($request, [
                     'identification' => 'required|unique:beds'
                  ]);
               }

               // Filling the model
               $bed->identification = $request->input('identification');
               $bed->save();

               // Return the ward information (if there is a ward)
               if($request->input('ward_id')) {
                  $ward = Ward::where('id', $request->input('ward_id'))->with('beds')->get()->first();
               } else {
                  $ward = '';
               }

               // Return 
               return response()->json([
                  'result' => 'success', 
                  'msg' => '<strong> Success!!!</strong> Bed created successfully.', 
                  'row' => Bed::where('id', $bed->id)->with('ward')->get(),
                  'ward' => Ward::with('beds')->get()
               ]);

            default:
               return response()->json(['result' => 'error', 'msg' => 'Undefined item.']);
               break;
         }
      }
   }

   /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request, $id) {
      if($request->ajax() && Auth::user()->role == 'master') {
         switch ($request->input('type')) {
            case 'ward':
               // Validating the fields
               $this->validate($request, [
                  'identification' => 'required|unique:wards,identification,'.$id,
                  'capacity' => 'required|integer'
               ]);

               // Filling the new ward
               $ward = Ward::find($id);
               $ward->identification = $request->input('identification');
               $ward->capacity = $request->input('capacity');
               $ward->save();

               // Json response
               return response()->json([
                  'result' => 'success', 
                  'msg' => '<strong> Success!!!</strong> Ward updated successfully.', 
                  'row' => $ward
               ]);

            case 'bed':
               $bed = Bed::find($id);
               // Validation
               if($request->input('ward_id')) {
                  $this->validate($request, [
                     'identification' => 'required|unique:beds,identification,'.$id,
                     'ward_id' => 'exists:wards,id'
                  ]);
                  
                  if($request->input('ward_id') != $bed->ward_id) {
                     $ward = Ward::where('id', $request->input('ward_id'))->with('beds')->get()->first();
                     
                     if(!$ward->capacity > count($ward->beds)) 
                        return response()->json([
                           'result' => 'error', 
                           'msg' => '<strong> Error!!!</strong> Ward is at max capacity.'
                        ]);                   
                  }

                  $bed->ward_id = $request->input('ward_id');
               } else {
                  $this->validate($request, [
                     'identification' => 'required|unique:beds,identification,'.$id,
                  ]);
               }

               $bed->identification = $request->input('identification');
               $bed->save();

               $ward = ($request->input('ward_id')) ? Ward::with('beds')->get() : '';

               return response()->json([
                  'result' => 'success', 
                  'msg' => '<strong> Success!!!</strong> Bed updated successfully.', 
                  'row' => Bed::where('id', $id)->with('ward')->get()->first(),
                  'ward' => $ward
               ]);

            default:
               return response()->json(['result' => 'error', 'msg' => 'Undefined item.']);
               break;
         }
      } else {
         $bed = Bed::find($id);

         switch ($request->input('action')) {
            case 'assign':
               $bed->patient_id = $request->input('patient');
               $bed->save();

               return response()->json([
                  'result' => 'success', 
                  'msg' => 'Success!!! Patient assigned successfully.', 
                  'row' => Bed::where('id', $id)->with('ward')->with('patient')->get()->first()
               ]);   
               break;
            
            case 'release':
               $bed->patient_id = NULL;
               $bed->save();

               return response()->json([
                  'result' => 'success', 
                  'msg' => 'Success!!! Bed released successfully.', 
                  'row' => Bed::where('id', $id)->with('ward')->with('patient')->get()->first()
               ]);   
               break;

            default:
               return response('Unauthorized.', 401);
               break;
         }
      }
   }

   /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function destroy(Request $request, $id) {
      // Only admit ajax request and admin user
      if ($request->ajax() && Auth::user()->role === 'master') {
         switch ($request->input('type')) {
            case 'ward':
               // Retrieving the ward and remove it (associated beds set null)
               if(Ward::destroy($id)) {
                  return response()->json(['result' => 'success', 'msg' => 'Ward removed successfully.']);
               } else {
                  return response()->json(['result' => 'error', 'msg' => 'Oops, try again.']);
               }
            case 'bed':
               // Retrieving the bed and remove it (associated tables set null)
               if(Bed::destroy($id)) {
                  return response()->json(['result' => 'success', 'msg' => 'Bed removed successfully.']);
               } else {
                  return response()->json(['result' => 'error', 'msg' => 'Oops, try again.']);
               }
            
            default:
               return response()->json(['result' => 'error', 'msg' => 'Undefined staff member.']);
         }
      } else {
         return response('Unauthorized.', 401);
      }
   }
}

?>