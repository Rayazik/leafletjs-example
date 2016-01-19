	  <div class="col-md-12">
      	<div class="row">
            <h1>Просмотр списка точек</h1>
        </div>
      </div>
      
   <?php if (!empty($message)): ?>
      <div class="col-md-12">
      		<div class="row">
      			<h5 style="font-weight: bold;color: <?=$success ? 'green' : 'red';?>;">{message}</h5>
      		</div>
      </div>
   <?php endif; ?>
      
      <div class="col-md-12">
      <div class="table">
      	<table class="table">
      	{points}
      		<tr>
      			<td>{id}</td>
      			<td>{description}</td>
      			<td>{lat}:{lon}</td>
      			<td><a onclick="return confirm('Вы уверены, что хотите удалить эту запись?');" href="/index.php/index/points/del/{id}">Удалить</a></td>
      		</tr>
      	{/points}
      	</table>
      </div>