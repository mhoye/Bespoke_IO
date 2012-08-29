<?php slot::set('head_title', 'contact us'); ?>
<?php slot::set('crumbs', 'contact us'); ?>

<?php if (isset($email_sent)): ?>
    <h3>Contact us</h3>
    <p>Your contact request has been sent.</p>
<?php else: ?>
    <?= 
    form::build(url::current(), array('class'=>'contact'), array(
        form::hidden('referer', @$_SERVER['HTTP_REFERER']),
        form::fieldset('Contact us', array(), array(
            '<p>All fields are required.</p>',

            form::field('input', 'name', 'Name', array('class'=>'required')),
            form::field('input', 'email', 'Email', array('class'=>'required')),
            form::field('dropdown', 'category', 'Category', array(
                'class' => 'required',
                'options' => array(
                    'general'       => 'Question About BeSDS (General Inquiries)',
                    'customization' => 'Customization Help',
                    'techsupport'   => 'Technical Support/Help',
                    'suggestion'    => 'Suggestions/Requests for Enhancements',
                ),
            )),
            form::field('textarea', 'comments', 'Comments', array('class'=>'required')),
            form::field('submit', 'contact', null, array('class'=>'required','value'=>'Contact us'))
        ))
    ));
    ?>
<?php endif ?>
