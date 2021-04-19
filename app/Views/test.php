<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<?php $value =0; ?>



<div class="form-group">
    <label for="exampleFormControlSelect2"> Change Status</label>
    <select   class="form-control" id="exampleFormControlSelect2">
      <option value="">Select One</option>
      <option value="1" <?= ($value == 1)? "selected = 'selected'" : "" ; ?>>Active</option>
        <option value="0" <?= ($value == 0)? "selected = 'selected'" : "" ; ?>>DeActive</option>
    </select>
  </div> 