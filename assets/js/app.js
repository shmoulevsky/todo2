
$(function(){
    
    $(document).on("click", "#auth-user", function(){  
        
        let login = $('#login').val();
        let pass = $('#pass').val();
    
        authUser(login, pass);
    });

    $(document).on("click", "#add-task", function(){  
        
        let title = $('#title').val();
        let email = $('#email').val();
        let user_name = $('#user_name').val();
        let description = $('#desc').val();
        let status = $('#status option:selected').val(); 
        let important = $('#important option:selected').val(); 

        addOrUpdate(0, title, email, user_name, description, status, important, '');
    });

    $(document).on("focusout paste", ".task-title, .task-description, .task-email, .task-user", function(){   
        
        let id = $(this).parent().parent().data('id');
        let title = $('#task-title-' + id).html().trim();
        let email = $('#task-email-' + id).html();
        let user_name = $('#task-user-' + id).html();
        let description = $('#task-description-' + id).html().trim();
        let important = $('#task-important-' + id).hasClass('active');
        let status = $(this).parent().parent().data('status');
        
        addOrUpdate(id, title, email, user_name, description, status, important, $(this));

    });

    $(document).on("focusin paste", ".task-title, .task-description", function(){   
        $('.editable-field').removeClass('active');
        $('.editable-field').removeClass('error');
    });

    $(document).on("click", ".delete-task", function(){

        let id = $(this).data('id');
    
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/handlers/tasks/delete/' + id,
            type: "GET",
            dataType: "json",
                data: ({}),
            success: function(data){
                  
                if(data.status == 'deleted') {$('#card-item-'+id).fadeOut();}
                
                                      
            },
            error : function(e) {
               console.log(e);
            }
        });

    });



    function addOrUpdate(id, title, email, user_name, description, status_id, important, $el){

                
        let err = [];

        title = title.toString().replace('<br>','');
        description = description.toString().replace('<br>','');

        $(".modal-status").fadeOut();

        let url = '/handlers/tasks/add';

        if(parseInt(id) > 0){
            url = '/handlers/tasks/edit';
        }

        if(title.length < 3){
            err.push('Заполните поле &laquo;Название&raquo; (мин. 3 символа)');
        }

        if(description.length < 3){
            err.push('Заполните поле &laquo;Описание&raquo; (мин. 3 символа)');
        }
        
        if(user_name.length < 3){
            err.push('Заполните поле &laquo;Имя пользователя&raquo; (мин. 3 символа)');
        }
       
        if(!validateEmail(email)){
            err.push('Введите корректный email');
        }

       if(err != ''){
            if(parseInt(id) > 0){
                $el.addClass('error');
            }else{

                let errText = '';

                for (let key in err) {
                    errText += err[key] + '</br>';
                }

                $(".modal-status").html('<div class="alert alert-danger" role="alert">'+errText+'</div>');
                $(".modal-status").fadeIn();
                            }
        }else{

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                dataType: "json",
                    data: ({title, email, user_name, description, status_id, important, id}),
                success: function(data){
                    
                    if(data.status == 'add'){
                        
                        let params = window.location.search;

                        if(!params) {
                            params = "?action=add"
                        } else {
                            params += "&action=add";
                        }
                        console.log(location.href);
                        location.href += params;
                           
                        
                    }

                    if(data.status == 'edit'){
                        $el.addClass('success');
                    }
                    
                    
                                        
                },
                error : function(e) {
                console.log(e);
                }
            });

        }

    }

    function authUser(login, pass) {
        
        let err = '';
        $('#auth-err').css('display','none');
        $('#auth-err').html('');


        if(login.length < 3){
            err += 'Мин. длина поля логин 3 символа';
        }

        if(pass.length < 3){
            err += '<br>Мин. длина поля пароль 3 символа';
        }
        if(err == ''){

        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/handlers/users/auth',
            type: "POST",
            dataType: "json",
                data: ({login,pass}),
            success: function(data){
                
                if(data.status == 'success'){
                    location.reload();
                }else{
                    $('#auth-err').fadeIn();
                    $('#auth-err').html(data.err);
                }
                                    
            },
            error : function(e) {
            
            }
        });

        }else{

            $('#auth-err').fadeIn();
            $('#auth-err').html(err);

        }

    }

    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    $("#backlog, #inwork, #finished").sortable({
        connectWith: '.task-col',
        cancel: 'input,textarea,button,select,option,[contenteditable]',
        receive: function(event, ui) {
          
          changeStatus(event,ui);
          
      }
    }).disableSelection();

    function changeStatus(event, ui) {
        
        var id = ui.item[0].dataset.id;
        var status_id = event.target.dataset.status;
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/handlers/tasks/status',
            type: "GET",
            dataType: "json",
                data: ({id, status_id}),
            success: function(data){
                                     
            },
            error : function(e) {
               console.log(e);
            }
        });
        

    }

    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, "");
    };
 
})

