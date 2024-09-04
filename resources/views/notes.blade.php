@extends('layouts.user_type.auth')

@section('content')
    <div class="card">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="plus-tab" data-bs-toggle="tab" href="#plus" role="tab" aria-controls="plus" aria-selected="true">Notes sur ceux qui ont eux des plus</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="moins-tab" data-bs-toggle="tab" href="#moins" role="tab" aria-controls="moins" aria-selected="false">Notes sur ceux qui ont eu des moins</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="plus" role="tabpanel" aria-labelledby="plus-tab">
                <div class="card-body">
                    <h2><center>Information sur vos étudiants ayant eu des plus</center></h2><br>
                    @if($notes_plus_iai->isNotEmpty() || $notes_plus_ucao->isNotEmpty() || $notes_plus_esg->isNotEmpty() || $notes_plus_univ->isNotEmpty() || $notes_plus_par->isNotEmpty())

                        @if($notes_plus_iai->isNotEmpty())
                            <h4><center><U>Lieu</U> : IAI-TOGO</center></h4>
                            @foreach ($notes_plus_iai as $note_plus_iai)
                                <p>Pour l'évènement intitulé : <b>{{ $note_plus_iai->evenement->titre }} du {{ $note_plus_iai->evenement->date }}</b>, voici les informations sur ceux qui ont des plus :<br><ul class="indent">
                                    @foreach(explode("\n", $note_plus_iai->contenu) as $ligne)
                                        @if(trim($ligne) !== '')
                                            <li><h5>{!! $ligne !!}</h5></li>
                                        @endif
                                    @endforeach
                                </ul></p>
                            @endforeach
                        @endif

                        @if($notes_plus_ucao->isNotEmpty())
                            <h4><center><U>Lieu</U> : UCAO</center></h4>
                            @foreach ($notes_plus_ucao as $note_plus_ucao)
                                <p>Pour l'évènement intitulé : <b>{{ $note_plus_ucao->evenement->titre }} du {{ $note_plus_ucao->evenement->date }}</b>, voici les informations sur ceux qui ont des plus :<br><ul class="indent">
                                    @foreach(explode("\n", $note_plus_ucao->contenu) as $ligne)
                                        @if(trim($ligne) !== '')
                                            <li><h5>{!! $ligne !!}</h5></li>
                                        @endif
                                    @endforeach
                                </ul></p>
                            @endforeach

                        @endif

                        @if($notes_plus_esg->isNotEmpty())
                        <h4><center><U>Lieu</U> : ESGIS</center></h4>
                        @foreach ($notes_plus_esg as $note_plus_esg)
                                <p>Pour l'évènement intitulé : <b>{{ $note_plus_esg->evenement->titre }} du {{ $note_plus_esg->evenement->date }}</b>, voici les informations sur ceux qui ont des plus :<br><ul class="indent">
                                    @foreach(explode("\n", $note_plus_esg->contenu) as $ligne)
                                        @if(trim($ligne) !== '')
                                            <li><h5>{!! $ligne !!}</h5></li>
                                        @endif
                                    @endforeach
                                </ul></p>
                            @endforeach

                        @endif

                        @if($notes_plus_par->isNotEmpty())
                            <h4><center><U>Lieu</U> : Collège de Paris</center></h4>
                            @foreach ($notes_plus_par as $note_plus_par)
                                <p>Pour l'évènement intitulé : <b>{{ $note_plus_par->evenement->titre }} du {{ $note_plus_par->evenement->date }}</b>, voici les informations sur ceux qui ont des plus :<br><ul class="indent">
                                    @foreach(explode("\n", $note_plus_par->contenu) as $ligne)
                                        @if(trim($ligne) !== '')
                                            <li><h5>{!! $ligne !!}</h5></li>
                                        @endif
                                    @endforeach
                                </ul></p>
                            @endforeach
                        @endif

                        @if($notes_plus_univ->isNotEmpty())
                            <h4><center><U>Lieu</U> : Université de Lomé</center></h4>
                            @foreach ($notes_plus_univ as $note_plus_univ)
                                <p>Pour l'évènement intitulé : <b>{{ $note_plus_univ->evenement->titre }} du {{ $note_plus_univ->evenement->date }}</b>, voici les informations sur ceux qui ont des plus :<br><ul class="indent">
                                    @foreach(explode("\n", $note_plus_univ->contenu) as $ligne)
                                        @if(trim($ligne) !== '')
                                            <li><h5>{!! $ligne !!}</h5></li>
                                        @endif
                                    @endforeach
                                </ul></p>
                            @endforeach

                        @endif
                    @else
                    <h3>Vous n'avez pas d'informations sur des étudiants ayant eu des plus enregistrées.</h3>
                    @endif
                </div>
            </div>


            <div class="tab-pane fade" id="moins" role="tabpanel" aria-labelledby="moins-tab">
                <div class="card-body">
                    <h2><center>Information sur vos étudiants ayant eu des moins</center></h2><br>
                    @if($notes_moins_iai->isNotEmpty() || $notes_moins_ucao->isNotEmpty() || $notes_moins_esg->isNotEmpty() || $notes_moins_univ->isNotEmpty() || $notes_moins_par->isNotEmpty())

                        @if($notes_moins_iai->isNotEmpty())
                            <h4><center><U>Lieu</U> : IAI-TOGO</center></h4>
                            @foreach ($notes_moins_iai as $note_moins_iai)
                                <p>Pour l'évènement intitulé : <b>{{ $note_moins_iai->evenement->titre }} du {{ $note_moins_iai->evenement->date }}</b>, voici les informations sur ceux qui ont des moins :<br><ul class="indent">
                                    @foreach(explode("\n", $note_moins_iai->contenu) as $ligne)
                                        @if(trim($ligne) !== '')
                                            <li><h5>{!! $ligne !!}</h5></li>
                                        @endif
                                    @endforeach
                                </ul></p>
                            @endforeach
                        @endif

                        @if($notes_moins_ucao->isNotEmpty())
                            <h4><center><U>Lieu</U> : UCAO</center></h4>
                            @foreach ($notes_moins_ucao as $note_moins_ucao)
                                <p>Pour l'évènement intitulé : <b>{{ $note_moins_ucao->evenement->titre }} du {{ $note_moins_ucao->evenement->date }}</b>, voici les informations sur ceux qui ont des moins :<br><ul class="indent">
                                    @foreach(explode("\n", $note_moins_ucao->contenu) as $ligne)
                                        @if(trim($ligne) !== '')
                                            <li><h5>{!! $ligne !!}</h5></li>
                                        @endif
                                    @endforeach
                                </ul></p>
                            @endforeach

                        @endif

                        @if($notes_moins_esg->isNotEmpty())
                            <h4><center><U>Lieu</U> : ESGIS</center></h4>
                            @foreach ($notes_moins_esg as $note_moins_esg)
                                <p>Pour l'évènement intitulé : <b>{{ $note_moins_esg->evenement->titre }} du {{ $note_moins_esg->evenement->date }}</b>, voici les informations sur ceux qui ont des moins :<br><ul class="indent">
                                    @foreach(explode("\n", $note_moins_esg->contenu) as $ligne)
                                        @if(trim($ligne) !== '')
                                            <li><h5>{!! $ligne !!}</h5></li>
                                        @endif
                                    @endforeach
                                </ul></p>
                            @endforeach

                        @endif

                        @if($notes_moins_par->isNotEmpty())
                            <h4><center><U>Lieu</U> : Collège de Paris</center></h4>
                            @foreach ($notes_moins_par as $note_moins_par)
                                <p>Pour l'évènement intitulé : <b>{{ $note_moins_par->evenement->titre }} du {{ $note_moins_par->evenement->date }}</b>, voici les informations sur ceux qui ont des moins :<br><ul class="indent">
                                    @foreach(explode("\n", $note_moins_par->contenu) as $ligne)
                                        @if(trim($ligne) !== '')
                                            <li><h5>{!! $ligne !!}</h5></li>
                                        @endif
                                    @endforeach
                                </ul></p>
                            @endforeach
                        @endif

                        @if($notes_moins_univ->isNotEmpty())
                            <h4><center><U>Lieu</U> : Université de Lomé</center></h3><br>
                            @foreach ($notes_moins_univ as $note_moins_univ)
                                <p>Pour l'évènement intitulé : <b>{{ $note_moins_univ->evenement->titre }} du {{ $note_moins_univ->evenement->date }}</b>, voici les informations sur ceux qui ont des moins :<br><ul class="indent">
                                    @foreach(explode("\n", $note_moins_univ->contenu) as $ligne)
                                        @if(trim($ligne) !== '')
                                            <li><h5>{!! $ligne !!}</h5></li>
                                        @endif
                                    @endforeach
                                </ul></p>
                            @endforeach

                        @endif
                    @else
                    <h3>Vous n'avez pas d'informations sur des étudiants ayant eu des moins enregistrées.</h3>
                    @endif

                </div>
            </div>
        </div>
        <br>
    </div>

@endsection

