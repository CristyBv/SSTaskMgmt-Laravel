<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function action(Request $request) {
            $query = $request->get('query');
            
            $content = "@switch(" . '$data' . "['filter'])
                @case('user_id')
                    @include('layouts.home_user')
                    @break
                @case('project_id')
                    @include('layouts.home_project')
                    @break
                @case('priority')
                    @include('layouts.home_priority')
                    @break
                @case('status')
                    @include('layouts.home_status')
                    @break
            @endswitch
            ";

            $data = [
                'tasks' => $query,
                'content' => $content,
                'test' => '<?php echo "Dadad"; ?>',
            ];

            echo json_encode($data);
        
    }
}
