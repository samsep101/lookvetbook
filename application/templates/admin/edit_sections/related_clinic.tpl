<?php

    $manager = ModelManagerFactory::getByName('clinic');

    require_once ABS_ROOT.'/application/models/relations.simplemodel.php';
    $model = new RelationsSimpleModel();

    $current_model_id = $this->model->id;

    $relations = $model->getServicesRelationsClinics($current_model_id);

    $clinics = [];
    if(!empty($relations)){

        $clinics = $manager->getClinics($relations);
    }
?>

<?php if(!empty($clinics)) : ?>

    <table class="list">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Адрес</th>
            <th>Тип</th>
            <th>Управление</th>
        </tr>
        <?php foreach($clinics as $clinic) : ?>
        <tr>
            <td><?=$clinic->id?></td>
            <td><a href="/admin/clinic/edit?id=<?=$clinic->id?>"><?=$clinic->name?></a></td>
            <td><?=$clinic->address?></td>
            <td><?=$clinic->type_name?></td>
            <td>
                <a href="/admin/clinic/edit?id=<?=$clinic->id?>">
                    <img title="Редактировать" border="0" class="edit-image" src="/media/admin/icons/pencil-16-ns.png">
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

<?php else : ?>
    <div class="alert alert-info">Связей пока нет.</div>
<?php endif; ?>

    <div class="tools-panel">
        <ul class="form">
            <li>
                <label>Связать с клиникой:</label>
                <input type="text" name="autocomplete_clinic" placeholder="Введите название клиники">
            </li>
        </ul>

        <input type="button" value="Добавить связь">
    </div>