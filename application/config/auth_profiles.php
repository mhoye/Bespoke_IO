<?php
/**
 * Configuration for auth profiles
 */
$config['secret']          = 'c2Vzc2lvbl9pZHxzOjMyOiJkNmY5NTUw';
$config['home_url']        = 'profiles/%1$s/';
$config['cookie_name']     = 'byob_auth_profiles';
$config['cookie_path']     = '/';
$config['cookie_domain']   = '';
$config['cookie_secure']   = false;
$config['cookie_httponly'] = true;

$config['base_anonymous_role'] = 'guest';
$config['base_login_role']     = 'member';

$config['roles'] = array(
    'guest'   => 'Guest', 
    'member'  => 'Regular member', 
    'trusted' => 'Trusted member', 
    'editor'  => 'Editor', 
    'admin'   => 'Administrator'
);

$acls = new Zend_Acl();
$config['acls'] = $acls

    ->addRole(new Zend_Acl_Role('guest'))
    ->addRole(new Zend_Acl_Role('member'), 'guest')
    ->addRole(new Zend_Acl_Role('trusted'), 'member')
    ->addRole(new Zend_Acl_Role('editor'), 'member')
    ->addRole(new Zend_Acl_Role('admin'), 'editor')

    // Admins can do anything.
    ->allow('admin')

    // Search privileges
    ->add(new Zend_Acl_Resource('search'))
    /* ->allow('guest', 'search', array(
        'search_repack'
    )) */
    ->allow('editor', 'search', array(
        'search', 'approvalqueue', 'search_repack'
    ))

    // Profile privileges
    ->add(new Zend_Acl_Resource('profiles'))
    ->allow('member', 'profiles', array(
        'view_own', 'edit_own',
    ))

    // Repack privileges
    ->add(new Zend_Acl_Resource('repacks'))
    ->allow('guest', 'repacks', array(
        'view_released', 'download_released',
    ))
    ->allow('member', 'repacks', array(
        'create', 'view_own', 
        'view_own_history', 'view_own_changes',
        'edit_own', 'delete_own', 
        'release_own', 'revert_own', 'cancel_own',
        'makepublic_own', 'makeprivate_own',
        'locale_selection', 'addon_management',
	// added by mhoye - in the BeSDS context, all users are trusted users.
	'approve_own', 'auto_approve_own',
	'addon_management_xpi_upload',
	'edit_distribution_ini',
	'certificate_management_pem_upload',
    'general_specs',
    'thunderbird_general_specs'
	
    ))
    ->allow('trusted', 'repacks', array(
        'approve_own', 'auto_approve_own'
    ))
    ->allow('editor', 'repacks', array(
        'view_unreleased', 'view_history', 
        'view_changes', 'view_approval_queue', 'view_private', 
        'see_failed',
        'distributionini', 'repackcfg', 'repacklog', 'repackjson',
        'edit', 'delete', 'release', 
        'revert', 'approve', 'reject', 
        'makepublic', 'makeprivate',
        'download_unreleased', 
        'edit_distribution_ini',
        'addon_management_xpi_upload',
        'certificate_management_pem_upload',
        'general_specs',
        'thunderbird_general_specs',
        'thunderbird_security',
        'thunderbird_ntlm',
        'thunderbird_addons',
        'thunderbird_lightning',
        'thunderbird_chat',
    ))

    // ORM Manager admin privileges
    ->add(new Zend_Acl_Resource('admin'))

    ;
