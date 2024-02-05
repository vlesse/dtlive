<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Models\Type;
use App\Models\TVShow;
use App\Models\Category;
use App\Models\Language;
use App\Models\Video;
use App\Models\App_Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Exception;

// Video Type = 1-Video, 2-Show, 3-Language, 4-Category, 5-Upcoming
class VideoSectionController extends Controller
{
    public function index()
    {
        try {

            $type = Type::get();
            return view('admin.video_section.index', ['type' => $type]);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function save(Request $request)
    {
        try {
            if (Auth::guard('admin')->user()->type != 1) {
                return response()->json(array('status' => 400, 'errors' => __('Label.You have no right to add, edit, and delete')));
            } else {

                if ($request->is_home_screen == 1) {
                    $validator = Validator::make($request->all(), [
                        'video_type' => 'required',
                        'title' => 'required|min:2',
                        'type_id' => 'required',
                        'video_id' => 'required',
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'title' => 'required|min:2',
                        'type_id' => 'required',
                        'video_id' => 'required',
                    ]);
                }

                if ($validator->fails()) {
                    $errs = $validator->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs));
                }

                $data = new App_Section();

                $data->type_id = $request->type_id;
                $data->title = $request->title;
                $data->video_type = $request->video_type;
                $data->screen_layout = $request->screen_layout;
                $data->is_home_screen = $request->is_home_screen;
                $data->upcoming_type = isset($request->upcoming_type) ? $request->upcoming_type : 0;
                $VideoId = implode(',', $request->video_id);
                $data->video_id = $VideoId;

                if ($data->save()) {
                    return response()->json(array('status' => 200, 'success' => __('Label.Data Add Successfully')));
                } else {
                    return response()->json(array('status' => 400, 'errors' => __('Label.Data Not Add')));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function get_all_data(Request $request)
    {
        try {

            if ($request->Video_Type == 1) {
                if ($request->Type_Id != "" && $request->Type_Id != null) {

                    $data = Video::where('type_id', $request->Type_Id)->where('video_type', $request->Video_Type)->get();
                    return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
                } else {
                    return response()->json(array('status' => 400, 'errors' => "Data Not Found"));
                }
            } else if ($request->Video_Type == 2) {

                if ($request->Type_Id != "" && $request->Type_Id != null) {

                    $data = TVShow::where('type_id', $request->Type_Id)->where('video_type', $request->Video_Type)->get();
                    return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
                } else {
                    return response()->json(array('status' => 400, 'errors' => "Data Not Found"));
                }
            } else if ($request->Video_Type == 3) {

                $data = Language::get();
                return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
            } else if ($request->Video_Type == 4) {

                $data = Category::get();
                return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
            } else if ($request->Video_Type == 5) {

                if ($request->Type_Id != "" && $request->Type_Id != null && $request->Upcoming_Type != "" && $request->Upcoming_Type != null) {

                    if ($request->Upcoming_Type == 1) {

                        $data = Video::where('type_id', $request->Type_Id)->where('video_type', $request->Video_Type)->get();
                    } elseif ($request->Upcoming_Type == 2) {
                        $data = TVShow::where('type_id', $request->Type_Id)->where('video_type', $request->Video_Type)->get();
                    }
                    return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
                } else {
                    return response()->json(array('status' => 400, 'errors' => "Data Not Found"));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function GetSectionData(Request $request)
    {
        try {
            if ($request->is_home_screen == 1) {

                $data = App_Section::where('is_home_screen', 1)->with('type')->orderBy('id', 'desc')->get();

                for ($i = 0; $i < count($data); $i++) {

                    $Multipal_Ids = explode(",", $data[$i]['video_id']);

                    if ($data[$i]['video_type'] == 1) {

                        $video = Video::select('id', 'name')->whereIn('id', $Multipal_Ids)->where('type_id', $data[$i]['type_id'])->get();

                        $videos = array();
                        for ($j = 0; $j < count($video); $j++) {
                            $videos[] = $video[$j]['name'];
                        }

                        if (isset($videos)) {
                            $html = [];
                            for ($k = 0; $k < count($videos); $k++) {
                                $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                            }
                            $html = implode(" ", $html);
                            $data[$i]['video_list'] = $html;
                        } else {
                            $data[$i]['video_list'] = "";
                        }
                    } else if ($data[$i]['video_type'] == 2) {

                        $video = TVShow::select('id', 'name')->whereIn('id', $Multipal_Ids)->where('type_id', $data[$i]['type_id'])->get();

                        $videos = array();
                        for ($j = 0; $j < count($video); $j++) {
                            $videos[] = $video[$j]['name'];
                        }

                        if (isset($videos)) {
                            $html = [];
                            for ($k = 0; $k < count($videos); $k++) {
                                $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                            }
                            $html = implode(" ", $html);
                            $data[$i]['video_list'] = $html;
                        } else {
                            $data[$i]['video_list'] = "";
                        }
                    } else if ($data[$i]['video_type'] == 3) {

                        $video = Language::select('id', 'name')->whereIn('id', $Multipal_Ids)->get();

                        $videos = array();
                        for ($j = 0; $j < count($video); $j++) {
                            $videos[] = $video[$j]['name'];
                        }

                        if (isset($videos)) {
                            $html = [];
                            for ($k = 0; $k < count($videos); $k++) {
                                $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                            }
                            $html = implode(" ", $html);
                            $data[$i]['video_list'] = $html;
                        } else {
                            $data[$i]['video_list'] = "";
                        }
                    } else if ($data[$i]['video_type'] == 4) {

                        $video = Category::select('id', 'name')->whereIn('id', $Multipal_Ids)->get();

                        $videos = array();
                        for ($j = 0; $j < count($video); $j++) {
                            $videos[] = $video[$j]['name'];
                        }

                        if (isset($videos)) {
                            $html = [];
                            for ($k = 0; $k < count($videos); $k++) {
                                $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                            }
                            $html = implode(" ", $html);
                            $data[$i]['video_list'] = $html;
                        } else {
                            $data[$i]['video_list'] = "";
                        }
                    } else if ($data[$i]['video_type'] == 5) {

                        if ($data[$i]['upcoming_type'] == 1) {

                            $video = Video::select('id', 'name')->whereIn('id', $Multipal_Ids)->where('type_id', $data[$i]['type_id'])->get();

                            $videos = array();
                            for ($j = 0; $j < count($video); $j++) {
                                $videos[] = $video[$j]['name'];
                            }

                            if (isset($videos)) {
                                $html = [];
                                for ($k = 0; $k < count($videos); $k++) {
                                    $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                                }
                                $html = implode(" ", $html);
                                $data[$i]['video_list'] = $html;
                            } else {
                                $data[$i]['video_list'] = "";
                            }
                        } else if ($data[$i]['upcoming_type'] == 2) {

                            $video = TVShow::select('id', 'name')->whereIn('id', $Multipal_Ids)->where('type_id', $data[$i]['type_id'])->get();

                            $videos = array();
                            for ($j = 0; $j < count($video); $j++) {
                                $videos[] = $video[$j]['name'];
                            }

                            if (isset($videos)) {
                                $html = [];
                                for ($k = 0; $k < count($videos); $k++) {
                                    $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                                }
                                $html = implode(" ", $html);
                                $data[$i]['video_list'] = $html;
                            } else {
                                $data[$i]['video_list'] = "";
                            }
                        }
                    }
                }

                return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
            } else if ($request->is_home_screen == 2) {
                $data = App_Section::where('is_home_screen', 2)->with('type')->where('type_id', $request->type_id)->orderBy('id', 'desc')->get();

                for ($i = 0; $i < count($data); $i++) {

                    $Multipal_Ids = explode(",", $data[$i]['video_id']);

                    if ($data[$i]['video_type'] == 1) {

                        $video = Video::select('id', 'name')->whereIn('id', $Multipal_Ids)->where('type_id', $data[$i]['type_id'])->get();

                        $videos = array();
                        for ($j = 0; $j < count($video); $j++) {
                            $videos[] = $video[$j]['name'];
                        }

                        if (isset($videos)) {
                            $html = [];
                            for ($k = 0; $k < count($videos); $k++) {
                                $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                            }
                            $html = implode(" ", $html);
                            $data[$i]['video_list'] = $html;
                        } else {
                            $data[$i]['video_list'] = "";
                        }
                    } else if ($data[$i]['video_type'] == 2) {

                        $video = TVShow::select('id', 'name')->whereIn('id', $Multipal_Ids)->where('type_id', $data[$i]['type_id'])->get();

                        $videos = array();
                        for ($j = 0; $j < count($video); $j++) {
                            $videos[] = $video[$j]['name'];
                        }

                        if (isset($videos)) {
                            $html = [];
                            for ($k = 0; $k < count($videos); $k++) {
                                $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                            }
                            $html = implode(" ", $html);
                            $data[$i]['video_list'] = $html;
                        } else {
                            $data[$i]['video_list'] = "";
                        }
                    } else if ($data[$i]['video_type'] == 3) {

                        $video = Language::select('id', 'name')->whereIn('id', $Multipal_Ids)->get();

                        $videos = array();
                        for ($j = 0; $j < count($video); $j++) {
                            $videos[] = $video[$j]['name'];
                        }

                        if (isset($videos)) {
                            $html = [];
                            for ($k = 0; $k < count($videos); $k++) {
                                $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                            }
                            $html = implode(" ", $html);
                            $data[$i]['video_list'] = $html;
                        } else {
                            $data[$i]['video_list'] = "";
                        }
                    } else if ($data[$i]['video_type'] == 4) {

                        $video = Category::select('id', 'name')->whereIn('id', $Multipal_Ids)->get();

                        $videos = array();
                        for ($j = 0; $j < count($video); $j++) {
                            $videos[] = $video[$j]['name'];
                        }

                        if (isset($videos)) {
                            $html = [];
                            for ($k = 0; $k < count($videos); $k++) {
                                $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                            }
                            $html = implode(" ", $html);
                            $data[$i]['video_list'] = $html;
                        } else {
                            $data[$i]['video_list'] = "";
                        }
                    } else if ($data[$i]['video_type'] == 5) {

                        if ($data[$i]['upcoming_type'] == 1) {

                            $video = Video::select('id', 'name')->whereIn('id', $Multipal_Ids)->where('type_id', $data[$i]['type_id'])->get();

                            $videos = array();
                            for ($j = 0; $j < count($video); $j++) {
                                $videos[] = $video[$j]['name'];
                            }

                            if (isset($videos)) {
                                $html = [];
                                for ($k = 0; $k < count($videos); $k++) {
                                    $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                                }
                                $html = implode(" ", $html);
                                $data[$i]['video_list'] = $html;
                            } else {
                                $data[$i]['video_list'] = "";
                            }
                        } else if ($data[$i]['upcoming_type'] == 2) {

                            $video = TVShow::select('id', 'name')->whereIn('id', $Multipal_Ids)->where('type_id', $data[$i]['type_id'])->get();

                            $videos = array();
                            for ($j = 0; $j < count($video); $j++) {
                                $videos[] = $video[$j]['name'];
                            }

                            if (isset($videos)) {
                                $html = [];
                                for ($k = 0; $k < count($videos); $k++) {
                                    $html[$k] = "<p class='btn btn-outline-dark btn-sm mr-2'>" . $videos[$k] . "</p>";
                                }
                                $html = implode(" ", $html);
                                $data[$i]['video_list'] = $html;
                            } else {
                                $data[$i]['video_list'] = "";
                            }
                        }
                    }
                }

                return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function edit(Request $request)
    {
        try {
            $App_Section = App_Section::where('id', $request->id)->first();

            if ($request->video_type == 1) {
                $video = Video::where('type_id', $request->type_id)->where('video_type', $request->video_type)->get();
            } else if ($request->video_type == 2) {
                $video = TVShow::where('type_id', $request->type_id)->where('video_type', $request->video_type)->get();
            } else if ($request->video_type == 3) {
                $video = Language::get();
            } else if ($request->video_type == 4) {
                $video = Category::get();
            } else if ($request->video_type == 5) {
                if ($request->upcoming_type == 1) {
                    $video = Video::where('type_id', $request->type_id)->where('video_type', $request->video_type)->get();
                } else if ($request->upcoming_type == 2) {
                    $video = TVShow::where('type_id', $request->type_id)->where('video_type', $request->video_type)->get();
                }
            }
            return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $App_Section, 'video' => $video));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function delete($id)
    {
        try {
            if (Auth::guard('admin')->user()->type != 1) {
                return response()->json(array('status' => 400, 'errors' => __('Label.You have no right to add, edit, and delete')));
            } else {
                $video = App_Section::where('id', $id)->first();
                if ($video) {
                    $video->delete();
                    return response()->json(array('status' => 200, 'success' => 'Data Deleted Successfully'));
                } else {
                    return response()->json(array('status' => 200, 'success' => 'Data Deleted Successfully'));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function update(Request $request)
    {
        try {
            if (Auth::guard('admin')->user()->type != 1) {
                return response()->json(array('status' => 400, 'errors' => __('Label.You have no right to add, edit, and delete')));
            } else {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|min:2',
                    'video_type' => 'required',
                    'type_id' => 'required',
                    'screen_layout' => 'required',
                    'video_id' => 'required',
                    'edit_id' => 'required',
                    'is_home_screen' => 'required',
                ]);

                if ($validator->fails()) {
                    $errs = $validator->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs));
                }

                $data = App_Section::where('id', $request->edit_id)->first();

                if (isset($data->id)) {

                    $data->type_id = $request->type_id;
                    $data->video_type = $request->video_type;
                    $data->title = $request->title;
                    $data->screen_layout = $request->screen_layout;
                    $data->is_home_screen = $request->is_home_screen;
                    $data->upcoming_type = isset($request->upcoming_type) ? $request->upcoming_type : 0;

                    $VideoId = implode(',', $request->video_id);
                    $data->video_id = $VideoId;

                    if ($data->save()) {
                        return response()->json(array('status' => 200, 'success' => __('Label.Data Edit Successfully')));
                    } else {
                        return response()->json(array('status' => 400, 'errors' => __('Label.Data Not Updated')));
                    }
                } else {
                    return response()->json(array('status' => 400, 'errors' => __('Label.Data Not Updated')));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
