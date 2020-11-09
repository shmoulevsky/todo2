<?
class TodoController extends BaseController
{
    public function index(Request $request)
    {
                
        $userId = UserModel::getId();
        $taskModel = new TaskModel();
        $statusModel = new StatusModel();

        $isAdmin = UserModel::isAdmin();
        $action = htmlspecialchars($request->get['action']);

        $nav = [];
        $filter = '';

        $sort = htmlspecialchars($request->get['sort']);
        $dir = 'desc';

        if($sort == ''){
            $sort = 'created_at';
        }

        if($sort == 'title' || $sort == 'user_name'){
            $dir = 'asc';
        }

        if($request->session['user']['group'] == 'user'){
            $filter = 'user_id = '.$userId;
        }
        
        
        if($request->session['user']['group'] == 'admin'){
            $filter = '';
        }

        if(htmlspecialchars($request->get['view']) == 'kanban'){

            $tasks = $taskModel->select('', $filter, $sort.' '.$dir)->group('status_id')->getArray();
            $view = 'todo';
            $statuses = $statusModel->select('', '', 'id asc')->getArray();
            
        }else{
            
            $count = PAGE_COUNT;
            $page = intval($request->get['page']) - 1;
           
           
            if($page < 0) $page = 0;
                        
            $tasks = $taskModel->select('', $filter, $sort.' '.$dir, $count, $page*$count, true)->getArray();
           
            $nav['pageCount'] = intval($taskModel->rowCount / $count);

            //for last tasks in page
            if($taskModel->rowCount % $count > 0){
                $nav['pageCount']++;
            }

            $nav['currentPage'] = intval($request->get['page']);

            if($nav['currentPage'] == 0){
                $nav['currentPage'] = 1;
            }

            $view = 'todo-list';
            $statuses = $statusModel->select('', '', 'id asc')->getArrayKey('id');
        }
        
                                
        $this->render($view, ['tasks' => $tasks, 'nav' => $nav,  'statuses' => $statuses, 'user' => $request->session['user'], 'sort' => $sort, 'isAdmin' => $isAdmin, 'action' => $action], 'Todo');

    }

    public function addOrEdit(Request $request)
    {
        $isAdmin = UserModel::isAdmin();

        $id = intval($request->post['id']);
        
        
            
            if($id == 0){

                $taskModel = new TaskModel(true);
                $taskModel->title = htmlspecialchars($request->post['title']);
                $taskModel->email = htmlspecialchars($request->post['email']);
                $taskModel->user_name = htmlspecialchars($request->post['user_name']);
                $taskModel->description	= htmlspecialchars($request->post['description']);
                $taskModel->user_id = UserModel::getId();
                $taskModel->status_id = intval($request->post['status_id']);
                $taskModel->created_at = date('Y-m-d H:i:s');
                $taskModel->updated_at = date('Y-m-d H:i:s');
                $taskModel->updated_by = 0;
                $taskModel->important = $request->post['important'];
                $id = $taskModel->save();
                $json = ['id' => $id, 'status' => 'add'];
    
            }else{
                
                if($isAdmin){

                    $date = date('Y-m-d H:i:s');
                    $taskModel = TaskModel::get($id);
                    $result = $taskModel->update(['title' => $request->post['title'], 'description' => $request->post['description'], 'email' => $request->post['email'], 'user_name' => $request->post['user_name'], 'updated_at' => $date, 'updated_by' => UserModel::getId()]);
                    $json = ['result' => $result, 'status' => 'edit'];

                }else{

                    $json = ['status' => 'access_deny'];
                }
                
            }
    
        
        
        echo json_encode($json);

    }
    
    public function delete(Request $request)
    {
        $isAdmin = UserModel::isAdmin();

        $json = [];
        $id = intval($request->params[0]);
    
        if($id > 0 && $isAdmin){
            TaskModel::delete($id);
            $json = ['id' => $id, 'status' => 'deleted'];
        }else{
            $json = ['id' => $id, 'status' => 'fail', 'er' => 'there is no task id'];
        }
        echo json_encode($json);
    }

    public function changeStatus(Request $request)
    {
        $json = [];
        $id = intval($request->get['id']);

        if($id > 0){

            $taskModel = TaskModel::get($id);
            $result = $taskModel->update(['status_id' => $request->get['status_id']]);
            $json = ['result' => $result, 'status' => 'updated'];
            
        }else{
            $json = ['id' => $id, 'status' => 'fail', 'er' => 'there is no task id'];
        }

        echo json_encode($json);
    }
}
?>