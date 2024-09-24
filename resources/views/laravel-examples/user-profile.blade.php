@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid">
        <!-- Modal -->
        <div class="card">
            <div class="modal fade" id="editPhotoModal" tabindex="-1" aria-labelledby="editPhotoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPhotoModalLabel">Modifier la photo de profil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <img src="{{ asset(Auth::user()->photo) }}" alt="Photo de profil" class="w-90 border-radius-lg shadow-sm " style="margin:25px">

                        <div class="modal-body">
                            <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="photo">Choisir une nouvelle photo :</label>
                                    <input type="file" name="photo" id="photo" class="form-control" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
            <span class="mask bg-gradient-primary opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6">
            <div class="row gx-4">

                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ asset(Auth::user()->photo) }}" alt="Photo de profil" class="w-100 border-radius-lg shadow-sm">
                        <button type="button" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2" data-bs-toggle="modal" data-bs-target="#editPhotoModal">
                            <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier la photo de profil"></i>
                        </button>
                    </div>
                </div>



                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ strtoupper(Auth::user()->name) }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ ucfirst(Auth::user()->role) }}
                        </p>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h4 class="mb-0">&nbsp;&nbsp;&nbsp;&nbsp;<u>{{ __('Information sur le profil') }}</u></h4>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="/user-profile" method="POST" role="form text-left">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('Nom') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ ucfirst(auth()->user()->name) }}" type="text" placeholder="Name" id="user-name" name="name" readonly>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user-email" class="form-control-label">{{ __('Email') }}</label>
                                <div class="@error('email')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ auth()->user()->email }}" type="email" placeholder="@example.com" id="user-email" name="email" readonly>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="role" class="form-control-label">{{ __('Rôle') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ ucfirst(auth()->user()->role) }}" type="text" placeholder="Role" id="role" name="role" readonly>



                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    @if(Auth::user()->role === 'professeur')
                        @if($events_blue->isNotEmpty()  || $events_green->isNotEmpty()  || $events_red->isNotEmpty())
                            <div class="form-group">
                                <div class="card-header pb-0 px-3">
                                    <h4 class="mb-0"><u>{{ __('Gestion des évènements') }}</u></h4>
                                </div>
                                <br>
                                @if($events_blue->isNotEmpty())

                                    <table  width=100%>

                                        <thead>
                                            <tr>
                                                <th colspan="2">Événements à présider</th>
                                                <th ></th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach ($events_blue as $event_blue)
                                                <tr>
                                                    <td><input type="text" class="form-control" value="{{ $event_blue->titre }}" title="{{ $event_blue->titre }}" readonly></td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                               value="{{ $event_blue->date .' de '. $event_blue->heure_debut.' à '.$event_blue->heure_fin.' | Lieu: '. $event_blue->institution }}"
                                                               readonly>
                                                    </td>
                                                    <td><div class="cercle-blue"></div></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    <br>
                                @endif

                                @if($events_green->isNotEmpty())

                                    <table  width=100%>

                                        <thead>
                                            <tr>
                                                <th colspan="2">Événements déjà faits</th>
                                                <th ></th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach ($events_green as $event_green)
                                                <tr>
                                                    <td><input type="text" class="form-control" value="{{ $event_green->titre }}" title="{{ $event_green->titre }}" readonly></td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                               value="{{ $event_green->date .' de '. $event_green->heure_debut.' à '.$event_green->heure_fin.' | Lieu: '. $event_green->institution }}"
                                                               readonly>
                                                    </td>

                                                    <td><div class="cercle-green"></div></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    <br>
                                @endif

                                @if($events_red->isNotEmpty())

                                    <table  width=100%>

                                        <thead>
                                            <tr>
                                                <th colspan="2">Événements ratés</th>
                                                <th ></th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach ($events_red as $event_red)
                                                <tr>
                                                    <td><input type="text" class="form-control" readonly value="{{ $event_red->titre }}" title="{{ $event_red->titre }}"></td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                               value="{{ $event_red->date .' de '. $event_red->heure_debut.' à '.$event_red->heure_fin.' | Lieu: '. $event_red->institution }}"
                                                               readonly>
                                                    </td>
                                                    <td><div class="cercle-red"></div></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    <br>
                                @endif

                            </div>
                        @endif
                    @endif
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('user-profile')
<style>
    .cercle-blue {
        width: 30px; /* Largeur du cercle */
            height: 30px; /* Hauteur du cercle */
            background-color: #500ad3; /* Couleur de fond du cercle */
            border-radius: 50%; /* Rend le div circulaire */
            display: inline-block; /* Pour que le cercle ne prenne pas plus d'espace que nécessaire */
    }
    .cercle-green {
        width: 30px; /* Largeur du cercle */
            height: 30px; /* Hauteur du cercle */
            background-color: #008a07; /* Couleur de fond du cercle */
            border-radius: 50%; /* Rend le div circulaire */
            display: inline-block; /* Pour que le cercle ne prenne pas plus d'espace que nécessaire */
    }
    .cercle-red {
        width: 30px; /* Largeur du cercle */
            height: 30px; /* Hauteur du cercle */
            background-color: #e00000; /* Couleur de fond du cercle */
            border-radius: 50%; /* Rend le div circulaire */
            display: inline-block; /* Pour que le cercle ne prenne pas plus d'espace que nécessaire */
    }
</style>
@endpush
