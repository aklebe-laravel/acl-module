<?php

namespace Modules\Acl\app\Forms;

use Modules\Form\app\Forms\Base\NativeObjectBase;

/**
 *
 */
class ImportAclGroup extends NativeObjectBase
{
    protected ?string $objectModelName = 'AclGroup';

    /**
     * Relation method if parent form exists.
     */
    const PARENT_RELATION_METHOD_NAME = 'aclGroups';

    /**
     *
     * @return array
     */
    public function getFormElements(): array
    {
        $parentFormData = parent::getFormElements();

        return [
            ... $parentFormData,
            'title'        => $this->makeFormTitle($this->jsonResource, 'name'),
            'tab_controls' => [
                'base_item' => [
                    //                    'disabled'  => true, // works for all elements
                    'tab_pages' => [
                        [
                            'tab'     => [
                                'label' => __('Common'),
                            ],
                            'content' => [
                                'form_elements' => [
                                    'media_file_upload' => [
                                        'html_element' => 'website-base::file_upload',
                                        'label'        => __('Media Upload'),
                                        'description'  => __('Media Upload'),
                                        'css_group'    => 'col-12 col-md-6',
                                    ],

                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}