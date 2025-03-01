<?php

namespace Modules\Acl\app\Http\Livewire\Form;

use Modules\Form\app\Http\Livewire\Form\Base\ModelBase;

class AclResource extends ModelBase
{
    /**
     * Relations commonly built in with(...)
     * Also used for:
     * - blacklist for properties to clean up the object if needed
     * - onAfterUpdateItem() to sync relations
     *
     * @var array[]
     */
    public array $objectRelations = ['aclGroups.users'];

    /**
     * Singular
     *
     * @var string
     */
    protected string $objectFrontendLabel = 'Acl Resource';

    /**
     * Plural
     *
     * @var string
     */
    protected string $objectsFrontendLabel = 'Acl Resources';

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
                        'common'     => [
                            'tab'     => [
                                'label' => __('Common'),
                            ],
                            'content' => [
                                'form_elements' => [
                                    'id'          => [
                                        'html_element' => 'hidden',
                                        'label'        => __('ID'),
                                        'validator'    => ['nullable', 'integer'],
                                    ],
                                    'code'        => [
                                        'html_element' => 'text',
                                        'label'        => __('Code'),
                                        'description'  => __('Unique resource code'),
                                        'validator'    => ['required', 'string', 'Max:255'],
                                        'css_group'    => 'col-6',
                                    ],
                                    'name'        => [
                                        'html_element' => 'text',
                                        'label'        => __('Name'),
                                        'description'  => __('Resource name'),
                                        'validator'    => ['required', 'string', 'Max:255'],
                                        'css_group'    => 'col-6',
                                    ],
                                    'description' => [
                                        'html_element' => 'textarea',
                                        'label'        => __('Description'),
                                        'description'  => __('Detailed description'),
                                        'validator'    => ['nullable', 'string', 'Max:30000'],
                                        'css_group'    => 'col-12',
                                    ],
                                ],
                            ],
                        ],
                        'acl_groups' => [
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Acl Groups'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'aclGroups' => [
                                        'html_element' => $defaultSettings['element_dt'],
                                        'label'        => __('Acl Groups'),
                                        'description'  => __('Acl groups linked to this resource'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'acl::form.acl-group',
                                            'table'         => 'acl::data-table.acl-group',
                                            'table_options' => [
                                                'hasCommands' => $defaultSettings['can_manage'],
                                                'editable'    => $defaultSettings['can_manage'],
                                                'canAddRow'   => $defaultSettings['can_manage'],
                                                'removable'   => $defaultSettings['can_manage'],
                                            ],
                                        ],
                                        'validator'    => ['nullable', 'array'],
                                    ],
                                ],
                            ],
                        ],
                        'users'      => [
                            // don't show if creating a new object ...
                            //'disabled' => !$this->getDataSource()->getKey(),
                            'tab'     => [
                                'label' => __('Users'),
                            ],
                            'content' => [
                                'form_elements' => [
                                    'users' => [
                                        'html_element' => 'element-dt-selected-no-interaction',
                                        'label'        => __('Users'),
                                        'description'  => __('Users for this Resource'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            //'form'          => 'website-base::form.user',
                                            'table'         => 'website-base::data-table.user',
                                            'table_options' => [
                                                'description'         => 'All users find of all groups by this acl resource.',
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
