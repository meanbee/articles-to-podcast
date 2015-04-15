@extends('layouts.master')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>

				<div class="panel-body">
					You are logged in!

					<p>
						Your podcast URL is: <tt>{{ route('podcast', array('id' => $user->id, 'secret' => $user->secret())) }}</tt>.
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
