<?php

namespace Modules\Acl\app\Http\Livewire\DataTable;

use Livewire\Attributes\On;
use Modules\Acl\app\Models\AclResource as AclResourceModel;
use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;
use Nette\NotImplementedException;

class AclResource extends BaseDataTable
{
    /**
     * Minimum restrictions to allow this component.
     */
    public const aclResources = [AclResourceModel::RES_DEVELOPER, AclResourceModel::RES_MANAGE_USERS];

    /**
     * @var string
     */
    public string $modelName = 'AclResource';

    /**
     * @return array[]
     */
    public function getColumns(): array
    {
        return [
            [
                'name'       => 'id',
                'label'      => 'ID',
                'format'     => 'number',
                'searchable' => true,
                'sortable'   => true,
                'css_all'    => 'hide-mobile-show-lg text-muted font-monospace text-end w-5',
            ],
            [
                'name'       => 'code',
                'label'      => 'Code',
                'searchable' => true,
                'sortable'   => true,
                'css_all'    => 'w-30',
            ],
            [
                'name'       => 'name',
                'label'      => 'Name',
                'searchable' => true,
                'sortable'   => true,
                'css_all'    => 'hide-mobile-show-md w-20',
            ],
            [
                'name'       => 'description',
                'label'      => 'Description',
                'searchable' => true,
                'sortable'   => true,
                'css_all'    => 'hide-mobile-show-lg w-20',
            ],
            [
                'name'       => 'updated_at',
                'label'      => 'Updated',
                'searchable' => true,
                'sortable'   => true,
                'view'       => 'data-table::livewire.js-dt.tables.columns.datetime-since',
                'css_all'    => 'hide-mobile-show-lg w-5',
            ],
        ];
    }

    /**
     * @param $livewireId
     * @param $aclResourceId
     *
     * @return bool
     */
    #[On('send-email')]
    public function sendEmail(mixed $livewireId, mixed $aclResourceId): bool
    {
        if (!$this->checkLivewireId($livewireId)) {
            return false;
        }

        throw (new NotImplementedException("Send Email not implemented yet"));
        // return false;
    }


}
