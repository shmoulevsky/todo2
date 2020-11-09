<nav class="navbar navbar-light bg-primary">
  <a class="navbar-brand text-light" href="/">
       Управление задачами v 1.1
  </a>
  <div>
  <?if(isset($user['group'])):?>  
  <span class="navbar-text text-light">Добро пожаловать, <?=$user['name']?> <?=$user['lastname']?>! (<?=$user['group']?>)</span>
  <a id="logout-user" href="/logout" role="button" class="btn btn-dark ml-3">Выйти</a>
  </div>
  <?else:?>
    <a id="logout-user" href="/auth" role="button" class="btn btn-dark ml-3">Вход</a>
  <?endif?>
</nav>
<div class="container">
  <div class="row">
    <div class="col-lg-2 top-button-wrap">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add-task-popup" >Добавить задачу</button>
    </div>
    <div class="col-lg-4 top-button-wrap">
        <span class="mr-3">Вид отображения:</span>
        <a href="/?view=kanban"  class="btn view-mode-btn link<?if($view == 'todo'):?> active<?endif?>" >Канбан</a>
        <a href="/?view=list"  class="btn view-mode-btn link<?if($view == 'todo-list'):?> active<?endif?>" >Список</a>
    </div>
    <?if($view == 'todo-list'):?>
    <div class="col-lg-6 top-button-wrap">
        <span class="mr-3">Сортировка:</span>
        <a href="/?view=list&page=<?=$nav['currentPage']?>&sort=created_at"  class="btn view-mode-btn link <?if($sort == 'created_at'):?> active<?endif?>" >По дате</a>
          <a href="/?view=list&page=<?=$nav['currentPage']?>&sort=user_name"  class="btn view-mode-btn link <?if($sort == 'user_name'):?> active<?endif?>" >По пользователю</a>
        <a href="/?view=list&page=<?=$nav['currentPage']?>&sort=title"  class="btn view-mode-btn link <?if($sort == 'title'):?> active<?endif?>" >По названию</a>
    </div>
    <?endif?>
    
  </div>
</div>

<div class="modal fade" id="add-task-popup" tabindex="-1" role="dialog" aria-labelledby="task-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="task-label">Добавить задачу</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="modal-status"></div>  
      <form>
        <div class="form-group">
          <label for="title">Название задачи</label>
          <input type="text" class="form-control" id="title" placeholder="">
        </div>
        <div class="form-group">
          <label for="user_name">Имя пользователя</label>
          <input type="text" class="form-control" id="user_name" placeholder="">
        </div>
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="text" class="form-control" id="email" placeholder="">
        </div>
        <div class="form-group">
          <label for="status">Статус</label>
          <select class="form-control" id="status">
            <?foreach ($statuses as $key => $status):?>
            <option value="<?=$status[id]?>"><?=$status[title]?></option>
            <?endforeach?>
          </select>
        </div>
        <div class="form-group">
          <label for="important">Срочность</label>
          <select class="form-control" id="important">
            <option value="0">Нет</option>
            <option value="1">Да</option>
          </select>
        </div>
        <div class="form-group">
          <label for="desc">Описание задачи</label>
          <textarea class="form-control" id="desc" rows="3"></textarea>
        </div>
      </form>
      </div>
      <div class="modal-footer">
        <button id="add-task" type="button" class="btn btn-primary">Сохранить</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>
<?if($action == 'add'):?>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">

        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Задача была добавлена</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    </div>
  </div>     
  <?endif?>