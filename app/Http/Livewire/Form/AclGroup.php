<?php

namespace Modules\Acl\app\Http\Livewire\Form;

use Modules\Form\app\Http\Livewire\Form\Base\ModelBase;

class AclGroup extends ModelBase
{
    /**
     * Relations commonly built in with(...)
     * Also used for:
     * - blacklist for properties to clean up the object if needed
     * - onAfterUpdateItem() to sync relations
     *
     * @var array[]
     */
    public array $objectRelations = [
        'aclResources',
        'users',
    ];

    /**
     * Singular
     *
     * @var string
     */
    protected string $objectFrontendLabel = 'Acl Group';

    /**
     * Plural
     *
     * @var string
     */
    protected string $objectsFrontendLabel = 'Acl Groups';

    /**
     *
     * @return array
     */
    public function getFormElements(): array
    {
        $parentFormData = parent::getFormElements();

        $defaultSettings = $this->getDefaultFormSettingsByPermission();

        return [
            ... $parentFormData,
            'title'        => $this->makeFormTitle($this->getDataSource(), 'name'),
            'tab_controls' => [
                'base_item' => [
                    'tab_pages' => [
                        'common'        => [
                            'tab'     => [
                                'label' => __('Common'),
                            ],
                            'content' => [
                                'form_elements' => [
                                    'id'          => [
                                        'html_element' => 'hidden',
                                        'label'        => __('ID'),
                                        'validator'    => [
                                            'nullable',
                                            'integer',
                                        ],
                                    ],
                                    'name'        => [
                                        'html_element' => 'text',
                                        'label'        => __('Name'),
                                        'description'  => __('Group name'),
                                        'validator'    => [
                                            'required',
                                            'string',
                                            'Max:255',
                                        ],
                                        'css_group'    => 'col-12',
                                    ],
                                    'description' => [
                                        'html_element' => 'textarea',
                                        'label'        => __('Description'),
                                        'description'  => __('Detailed description'),
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:30000',
                                        ],
                                        'css_group'    => 'col-12',
                                    ],
                                ],
                            ],
                        ],
                        'acl_resources' => [
                            // don't show if creating a new object ...
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Acl Resources'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'aclResources' => [
                                        'html_element' => $defaultSettings['element_dt'],
                                        'label'        => __('Acl Resources'),
                                        'description'  => __('Acl resources linked to this group'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'acl::form.acl-resource',
                                            'table'         => 'acl::data-table.acl-resource',
                                            'table_options' => [
                                                'hasCommands' => $defaultSettings['can_manage'],
                                                'editable'    => $defaultSettings['can_manage'],
                                                'canAddRow'   => $defaultSettings['can_manage'],
                                                'removable'   => $defaultSettings['can_manage'],
                                            ],
                                        ],
                                        'validator'    => [
                                            'nullable',
                                            'array',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'users'         => [
                            // don't show if creating a new object ...
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Users'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'users' => [
                                        'html_element' => $defaultSettings['element_dt'],
                                        'label'        => __('Users'),
                                        'description'  => __('Users linked to this group'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'website-base::form.user',
                                            'table'         => 'website-base::data-table.user',
                                            'table_options' => [
                                                'description'         => "",
                                                'filterByParentOwner' => false,
                                            ],
                                        ],
                                        'validator'    => [
                                            'nullable',
                                            'array',
                                        ],
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
