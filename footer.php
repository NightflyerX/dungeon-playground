<?php

?>

	</div>
	<div class="col-lg-4 h-100 ">
		<div id="chatdiv" class="container-fluid chatdiv border-left border-light p-2 text-white" style="height:700px;overflow-y:auto;"></div>
		<div class="input-group mt-3">
			<input id="chatinput" type="text" class="form-control-lg w-75 bg-dark text-white" placeholder="<?=$l[$lang]['FOOTER_MSG'];?>" aria-label="Some text" aria-describedby="basic-addon2">
			<div class="input-group-append">
				<button id="chatbutton" class="btn btn-primary" type="button"><?=$l[$lang]['FOOTER_CHAT'];?></button>
			</div>
		</div>
		<div class="mt-3" id="pooltoken">
		</div>
	</div>
</div>
</div>

</body>
</html>