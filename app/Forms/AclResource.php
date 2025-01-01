<?php

namespace Modules\Acl\app\Forms;

use Modules\Form\app\Forms\Base\ModelBase;

class AclResource extends ModelBase
{
    /**
     * Relations commonly built in with(...)
     * * Also used for:
     * * - blacklist for properties to cleanup the object if needed
     * * - onAfterUpdateItem() to sync relations
     *
     * @var array[]
     */
    //    protected array $objectRelations = ['aclGroups', 'users'];
    protected array $objectRelations = ['aclGroups.users'];

    /**
     * Singular
     * @var string
     */
    protected string $objectFrontendLabel = 'Acl Resource';

    /**
     * Plural
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

        // $defaultSettings = $this->getDefaultFormSettingsByPermission();

        return [
            ... $parentFormData,
            'title'        => $this->makeFormTitle($this->getDataSource(), 'name'),
            'tab_controls' => [
                'base_item' => [
                    'tab_pages' => [
                        [
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
                        [
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Acl Groups'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'aclGroups' => [
                                        'html_element' => 'element-dt-split-default',
                                        'label'        => __('Acl Groups'),
                                        'description'  => __('Acl groups linked to this resource'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'table' => 'acl::data-table.acl-group',
                                        ],
                                        'validator'    => ['nullable', 'array'],
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