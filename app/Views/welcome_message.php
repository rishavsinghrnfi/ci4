 
<form enctype="multipart/form-data" id="submit">
               <div class="form-group">
               <label for="menu">Select Menu</label>
                   <select class="form-control" name="selectmenuid" id="selectmenuid">
                    <option value="">-- Select Menu --</option>
                    <?php foreach($showData as $show):?>
                      <option value="<?php echo $show->menu_id?>"><?php echo $show->menu_name?></option>
                    <?php endforeach;?>
                    </select>
                </div>
               <div class="form-group">
               <label for="menu">Select Sub Menu</label>
                   <select class="form-control" name="selectsubmenu" id="selectsubmenu">
                       <option>--Select Sub Menu--</option>
                    </select>
               </div>
              <div class="form-group">
                  <label for="imagetitle">Image Title</label>
                  <input type="text" class="form-control" name="imagetitle" id="imagetitle" placeholder="Enter Image Title" required="required">
                </div>
               <div class="control-group form-group">
                        <div class="controls">
                            <label>Upload Photo:</label>
                            <input name="file" type="file"  id="image_file" required>
                            <p class="help-block"></p>
                        </div>
               </div>
               <button type="submit" class="btn btn-primary" id="sub">Submit</button>
           </form>  
 