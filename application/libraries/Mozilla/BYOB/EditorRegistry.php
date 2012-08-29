<?php
/**
 * BYOB editor module registry
 *
 * @package    BYOB
 * @subpackage Libraries
 * @author     l.m.orchard <lorchard@mozilla.com>
 */
class Mozilla_BYOB_EditorRegistry {

    public static $editors = array();

    /**
     * Register an editor instance.
     */
    public static function register($editor)
    {
        self::$editors[$editor->id] = $editor;
    }

    /**
     * Inform all editors that locale is ready
     */
    public static function l10n_ready()
    {
        foreach (self::$editors as $editor) {
            $editor->l10n_ready();
        }
    }

    /**
     * Return an editor by ID.
     *
     * @param   string $id editor ID
     * @returns Mozilla_BYOB_Editor
     */
    public static function findById($id)
    {
        if (empty(self::$editors[$id])) return null;
        return self::$editors[$id];
    }

    /**
     * Get a list of all sections
     */
    public static function getSections($repack)
    {
//        $sections = array(
//            'general' => _('General')
//        );

        if ($repack->repack_for == "firefox"){
            foreach (self::$editors as $editor_id => $editor) {
                if (!$editor->isAllowed($repack)) continue;
                if ($editor->id == 'adhoc_distribution_ini') continue;
                if ($editor->id == 'certificate_management') continue;
                if ($editor->id == 'thunderbird_general') continue;
                if ($editor->id == 'thunderbird_security') continue;
                if ($editor->id == 'thunderbird_ntlm') continue;
                if ($editor->id == 'thunderbird_addons') continue;
                if ($editor->id == 'thunderbird_lightning') continue;
                if ($editor->id == 'thunderbird_chat') continue;
                $sections[$editor->id] = $editor->title;
            }
            
            // TODO: Refactor away from this:
            foreach (Repack_Model::$edit_sections as $n=>$l) {
                if ('review' === $n) continue;
                if ('general' === $n) continue;
                $sections[$n] = $l;
            }
            foreach (self::$editors as $editor_id => $editor) {
                if ($editor_id == 'certificate_management')
                    $sections[$editor->id] = $editor->title;
                if ($editor_id == 'adhoc_distribution_ini') 
                    $sections[$editor->id] = $editor->title;
                if ($editor->id == 'thunderbird_general') continue;
            }

            $sections['review'] = false;
        } else { // Thunderbird
            $sections['thunderbird_general'] = 'General';
            $sections['thunderbird_security'] = 'Security';
            $sections['thunderbird_ntlm'] = 'NTLM Authentication';
            $sections['thunderbird_addons'] = 'Addons'; 
            $sections['thunderbird_lightning'] = 'Lightning';
            $sections['thunderbird_chat'] = 'Chat';
            $sections['review'] = false; 
        }
        return $sections;
    }

}
