<div class="box-body">
    <div class="form-group"><br>
    <div class="row">
        {{ Form::label('fichero', 'Fichero Origen:', ['class' => 'col-sm-4 control-label']) }} 
    </div>
        <div class="row">
            <div class="col-md-6">
                
    		    {{ Form::file('excel', ['class' => 'form-control ','id' => 'file','required' => true]) }}  
            </div>
            <div class="col-md-6">
                <button id="btn-subir" type="submit" class="btn btn-primary btn-flat">Subir Archivo</button>
            </div>

        </div>
    </div>
  </div>