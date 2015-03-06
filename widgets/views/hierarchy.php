
<table class="directory-modal-table directory-stretch-bar">
    <tr>
        <td>
            <div>
                <?php 
                $rootBranches = $hierarchy->getRootBranches()->all();
                foreach ($rootBranches as $rootBranch) : 
                    echo app\modules\directory\widgets\HierarchBranch::widget(['brabch'=>$rootBranch, 'previevSelector'=>'#node'.$uid]);
                endforeach; ?>
            </div>
        </td>
        <td>
            <div id="node<?=$uid?>"></div>
        </td>
    </tr>
</table>