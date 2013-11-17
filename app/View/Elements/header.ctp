<h1>
    <?php if(AuthComponent::user('profile_picture_id')){
        echo $this->Html->image('/files/');
    }else{
        if(AuthComponent::user('gender')=='male'){
            echo $this->Html->image('blank_image_male.jpeg',array('class'=>'profile_img','width'=>30,'height'=>30));
        }else{
            echo $this->Html->image('blank_image_female.jpeg',array('class'=>'profile_img','width'=>30,'height'=>30));
        }
    }
    ?>
    <!--            <img src="https://graph.facebook.com/--><?// echo AuthComponent::user('facebookid'); ?><!--/picture">-->
    <?php echo $this->Html->link('Hi '.AuthComponent::user('first_name').', '.ucfirst(AuthComponent::user('Role.role')),'/my-profile');?>
</h1>


<?php echo $this->Html->link('Logout',array('plugin'=>'usermgmt','controller'=>'users','action'=>'logout'),array('class'=>'logout'));?>
<?php echo $this->Html->link('Dashboard',array('plugin'=>false,'controller'=>'dashboards','action'=>'index'),array('class'=>'dashboard'));?>