<div class="box-body">
    <p>
    {!! Form::normalInput('codigo', 'Codigo', $errors) !!}
    {!! Form::normalInput('nombre', 'Nombre', $errors) !!}
    <textarea id="descripcion" name="descripcion" placeholder="Descripción del Producto" style="resize:none;width:100%;" class="form-group" rows="10"></textarea>
    {!! Form::normalInputOfType('number','stock', 'Stock', $errors) !!}
    {!! Form::normalInputOfType('number','stock_critico', 'Stock Critico', $errors) !!}
    {!! Form::normalInputOfType('number','costo', 'Costo', $errors) !!}
    {!! Form::normalInputOfType('number','precio', 'Precio', $errors) !!}
    <div class="form-group">
        {!! Form::label('image','Foto')!!}                                    
        <img id="preview" src="#" width="150" height="150" style="margin:2%;display:none;object-fit:cover"/>   
        {!! Form::file('image',['class' => 'form-control','id' => 'img-input']) !!}
    </div>
    
    </p>
</div>
