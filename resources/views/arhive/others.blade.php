// $searched = $request->searchproject;
// if($searched != null)
//     $projects = $projects->filter(function ($value, $key) use ($searched) {
//         return false !== stristr($value->title, $searched);
//     });

// $page = $request->page;
// $perPage = Config::get('projects')['perPage'];

// $paginator = new Paginator($projects->forPage($page, $perPage), count($projects), $perPage, $page, [
//     'path'  => $request->url(),
//     'query' => $request->query(),
// ]);

// $users = array();
// $users_count = User::orderByDesc('count')->take(5)->get();
// foreach($users_count as $user) {
//     $var = [$user->id => $user->name];
//     $users = $users + $var;
// }

// $projects = array();
// $projects_count = Project::orderByDesc('count')->take(5)->get();
// foreach($projects_count as $proj) {
//     $var = [$proj->id => $proj->title];
//     $projects = $projects + $var;
// }