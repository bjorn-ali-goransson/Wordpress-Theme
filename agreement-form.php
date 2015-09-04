<?php
  if(!function_exists('my_form_field')){
    function my_form_field($field_name, $field_label, $input_type = NULL, $required = FALSE){
      $field_id = rand(1, 999) . '_' . $field_name;

      ?>
        <div class="form-group" ng-class="{'has-error': form.<?php echo $field_name; ?>.$invalid && showValidationErrors}">
          <label for="<?php echo $field_id; ?>" class="control-label col-sm-3"><?php echo $field_label; ?></label>
          <div class="col-sm-9 position--relative">
            <?php if($input_type == 'textarea'){ ?>
              <textarea class="form-control" name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>" ng-model="agreement.<?php echo $field_name; ?>"<?php echo $required ? ' required' : ''; ?>></textarea>
            <?php } else if(!is_string($input_type) && is_callable($input_type)) {
              $input_type($field_id, $field_name);
            } else { ?>
              <input type="<?php echo $input_type ? $input_type : 'text'; ?>" class="<?php echo $input_type == 'file' ? '' : 'form-control'; ?>" name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>" ng-model="agreement.<?php echo $field_name; ?>"<?php echo $required ? ' required' : ''; ?>>
            <?php } ?>
            <span class="fa fa-warning form-control-feedback" ng-if="form.<?php echo $field_name; ?>.$invalid && showValidationErrors"></span>
            <span class="help-block" ng-if="form.<?php echo $field_name; ?>.$error.required && showValidationErrors">Detta fält krävs</span>
          </div>
        </div>
      <?php
    }
  }
?>

<div class="form-horizontal">
  <?php my_form_field('seller', 'Säljare', function($field_id, $field_name){ ?>
    <select class="form-control" name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>" ng-model="agreement.<?php echo $field_name; ?>" required>
      <?php
        foreach(my_get_users() as $user){
          ?>
            <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
          <?php
        }
      ?>
    </select>
  <?php }, TRUE); ?>
  <?php my_form_field('creation_date', 'Datum då avtalet skrevs', function($field_id, $field_name){ ?>
    <input type="text" class="form-control" name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>" ng-model="agreement.<?php echo $field_name; ?>" ng-model-options="{ updateOn: 'blur' }" placeholder="YYYY-MM-DD" required>
  <?php }, TRUE); ?>
  <?php my_form_field('number_of_years', 'Servicegaranti', function($field_id, $field_name){ ?>
    <select class="form-control" name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>" ng-model="agreement.<?php echo $field_name; ?>" required>
      <?php
        for($i = 1; $i < 16; $i++){
          ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?> år</option>
          <?php
        }
      ?>
    </select>
  <?php }, TRUE); ?>
  <?php my_form_field('visits', 'Besök', function(){ ?>
    <span ng-repeat="visit in agreement.visits">
      <span class="visit" ng-if="visit.visible">
        <span class="form-control">{{ visit.year }}</span>
        <span class="btn btn-default" title="Systemet fanns inte detta år" ng-if="(visit.status == 'system-did-not-exist')" ng-click="visit.status = 'visited'">
          <i class="fa fa-question-circle"></i>
        </span>
        <span class="btn btn-default" title="Ej besökt" ng-if="(visit.status == 'not-visited')" ng-click="visit.status = 'visited'">
          <i class="fa fa-circle-o"></i>
        </span>
        <span class="btn btn-default" title="Besökt" ng-if="(visit.status == 'visited')" ng-click="visit.status = 'Ej hemma'">
          <i class="fa fa-check-circle"></i>
        </span>
        <span class="btn btn-default" title="{{ visit.status }}" ng-if="!(visit.status == 'system-did-not-exist') && !(visit.status == 'not-visited') && !(visit.status == 'visited')" ng-click="visit.status = 'not-visited'">
          <i class="fa fa-question-circle"></i>
        </span>
      </span>
    </span>
  <?php }); ?>
  <?php my_form_field('file', 'Bifoga avtal', function(){
    ?>
      <div class="clearfix" ng-hide="agreement.file">
        <div class="btn btn-default pull-left" ngf-select ng-model="filesToUpload" ng-disabled="fileUploading"><i class="fa fa-paperclip" ng-if="!fileUploading"></i><i class="fa fa-spin fa-refresh" ng-if="fileUploading"></i> Ladda upp</div>
        <div class="progress pull-left" style="width: 300px; height: 39px; margin-left: 20px;" ng-if="fileUploading">
          <div class="progress-bar" style="line-height: 39px; width: {{ fileUploadProgress }}%;">
            {{ fileUploadProgress }}%
          </div>
        </div>
      </div>
      <a href="{{ agreement.file.url }}" target="_blank" ng-hide="!agreement.file">
        <img ng-src="{{ agreement.file.thumbnail }}">
      </a>
    <?php
  }); ?>
  <div ng-if="!agreement.id">
    <?php my_form_field('comment', 'Notering', 'textarea'); ?>
  </div>
  <div class="panel panel-default" ng-if="agreement.id">
    <div class="panel-heading">Noteringar</div>
    <div class="panel-body clearfix">
      <div class="media" ng-repeat="comment in agreement.comments" ng-if="agreement.comments">
        <div class="media-left">
          <i class="media-object fa fa-sx fa-file-text-o"></i><br>
        </div>
        <div class="media-body">
          <strong>{{ comment.user_name }} ({{ comment.date }}):</strong><br>
          {{ comment.comment }}
        </div>
      </div>
      <textarea class="form-control" style="margin: 15px 0;" ng-model="comment" ng-disabled="submitting" ng-hide="!agreement.comments.length"></textarea>
      <textarea class="form-control" style="margin: 0 0 15px;" ng-model="comment" ng-disabled="submitting" ng-hide="agreement.comments.length"></textarea>
      <button class="btn btn-default btn-primary pull-right" add-comment><i class="fa fa-plus" ng-if="!submitting"></i><i class="fa fa-spin fa-refresh" ng-if="submitting"></i> Lägg till notering</button>
    </div>
  </div>
</div>
<fieldset class="form-horizontal">
  <legend>Kontohavare</legend>
  <?php my_form_field('applicant_name', 'Namn', 'text', TRUE); ?>
  <?php my_form_field('applicant_social_security_number', 'Personnummer', 'text'); ?>
  <?php my_form_field('applicant_telephone_number', 'Telefonnummer', 'text'); ?>
  <?php my_form_field('applicant_address', 'Adress', 'text', TRUE); ?>
  <?php my_form_field('applicant_postal_code', 'Postnummer', 'text'); ?>
  <?php my_form_field('applicant_municipiality', 'Postadress', 'text', TRUE); ?>
  <p><label><input type="checkbox" ng-model="coApplicantExists"> Medsökande finns</label></p>
</fieldset>
<fieldset class="form-horizontal" ng-if="coApplicantExists">
  <legend>Medsökande</legend>
  <?php my_form_field('co_applicant_name', 'Namn'); ?>
  <?php my_form_field('co_applicant_social_security_number', 'Personnummer'); ?>
  <?php my_form_field('co_applicant_telephone_number', 'Telefonnummer'); ?>
  <?php my_form_field('co_applicant_address', 'Adress'); ?>
  <?php my_form_field('co_applicant_postal_code', 'Postnummer'); ?>
  <?php my_form_field('co_applicant_municipiality', 'Postadress'); ?>
</fieldset>
<fieldset>
  <legend>Specifikation</legend>
  <table class="table" style="table-layout: fixed;">
    <thead>
      <tr>
        <th class="column-1">Antal</th>
        <th class="column-2">Typ</th>
        <th class="column-3">Pris</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="(i, row) in agreement.specification">
        <td><input type="number" class="form-control" ng-model="row.count" name="specification_count[]"></td>
        <td><input type="text" class="form-control" ng-model="row.type" name="specification_type[]"></td>
        <td>
          <div class="input-group">
            <input type="number" class="form-control" ng-model="row.price" name="specification_price[]">
            <span class="input-group-btn">
              <button class="btn btn-default" ng-click="agreement.specification.splice($index, 1)"><i class="fa fa-times"></i></button>
            </span>
          </div>
        </td>
      </tr>
    </tbody>
  </table>

  <p class="clearfix">
    <button class="btn btn-default pull-right" ng-click="agreement.specification.push({})">Lägg till rad <i class="fa fa-plus"></i></button>
    <label class="pull-left">Vill delbetala summan <input type="checkbox" ng-model="agreement.split_payment" name="split_payment" value="true"></label>
  </p>
</fieldset>
<p class="clearfix">
  <button type="submit" class="btn btn-default btn-primary pull-right" ng-disabled="submitting"><i class="fa fa-save" ng-if="!submitting"></i><i class="fa fa-spin fa-refresh" ng-if="submitting"></i> Spara</button>
  <button class="btn btn-default btn-danger" delete-agreement ng-disabled="submitting" ng-if="agreement.id"><i class="fa fa-times" ng-if="!submitting"></i><i class="fa fa-spin fa-refresh" ng-if="submitting"></i> Ta bort</button>
</p>