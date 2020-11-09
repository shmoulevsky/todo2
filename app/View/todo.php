<?require_once APP_DIR. '/View/todo-actions.php'?>
<div class="container">
  <div class="row">
    <?foreach ($statuses as $key => $status):?>
      
      <div class="col-sm">
        <span class="badge <?=$status['class']?>"><?=$status['title']?></span> 
        <div id="<?=$status[code]?>" class="task-col" data-status="<?=$status[id]?>">
        <?if(count($tasks[$status['id']]) > 0):?>   
        <?foreach ($tasks[$status['id']] as $task):?>
          <div id="card-item-<?=$task[id]?>" data-id="<?=$task[id]?>" data-status="<?=$status[id]?>" class="card card-item">
            <div class="card-body">
              <p><?=$task[created_at]?></p>
              <span class="task-user editable-field" <?if($isAdmin):?>contenteditable="true"<?endif?> id="task-user-<?=$task[id]?>"><?=$task[user_name]?></span>
              <span class="text-success task-email editable-field" <?if($isAdmin):?>contenteditable="true"<?endif?> id="task-email-<?=$task[id]?>"><?=$task[email]?></span>
             
              <h5 <?if($isAdmin):?>contenteditable="true"<?endif?> id="task-title-<?=$task[id]?>" class="card-title task-title editable-field"><?=$task[title]?></h5>
              <p <?if($isAdmin):?>contenteditable="true"<?endif?> id="task-description-<?=$task[id]?>" class="card-text task-description editable-field"><?=$task[description]?></p>
              <?if($task[important]):?><span id="task-important-<?=$task[id]?>" class="badge badge-danger">Срочно!</span><?endif?>
                <?if($isAdmin):?><span data-id="<?=$task[id]?>" class="delete-btn delete-task"></span><?endif?>
            </div>
          </div>
        <?endforeach?>
        <?else:?>
        <?endif?>
      </div>
      </div>
    <?endforeach?> 
     
    </div>
  </div>
</div>