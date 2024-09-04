@extends('layouts.user_type.auth')

@section('content')
    @if(auth()->user()->role == 'professeur')
    <script type="text/javascript">
        window.location = "{{ url('dashboard-du-prof') }}";
    </script>
    @else
        <div class="container-fluid py-4">
            <div class="row">


                <!-- Vue d'ensemble des professeurs -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Vue d'ensemble des Professeurs</h6>
                        </div>
                        <div class="card-body p-3">
                            <p>Total de professeurs : <strong>{{ $totalProfessors }}</strong></p>
                            <p>Professeurs actifs : <strong>{{ $activeProfessors }}</strong></p>
                            <p>Professeurs inactifs : <strong>{{ $inactiveProfessors }}</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Statistiques des cours -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Statistiques des Cours</h6>
                        </div>
                        <div class="card-body p-3">
                            <p>Total de cours : <strong>{{ $totalCourses }}</strong></p>
                            <p>Cours réalisés : <strong>{{ $completedCourses }}</strong></p>
                            <p>Cours en attente : <strong>{{ $pendingCourses }}</strong></p>
                            <p>Cours ratés : <strong>{{ $missedCourses }}</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Gestion des institutions -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Gestion des Institutions</h6>
                        </div>
                        <div class="card-body p-3">
                            <p>Total d'institutions : <strong>{{ $totalInstitutions }}</strong></p>
                            <h6>Institutions les plus actives</h6>
                            <ul>
                                @foreach($activeInstitutions as $institution)
                                    <li>{{ $institution->nom }} : {{ $institution->evenements_count }} événements</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Professeurs Actifs vs Inactifs</h6>
                        </div>
                        <div class="card-body p-3">
                            <canvas id="professor-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Répartition des Cours</h6>
                        </div>
                        <div class="card-body p-3">
                            <canvas id="course-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Activité des Institutions</h6>
                        </div>
                        <div class="card-body p-3">
                            <canvas id="institution-chart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Notifications restant à envoyer</h6>
                        </div>
                        <div class="card-body p-3">
                            @foreach($notifications as $notification)
                            <div class="alert alert-info" style="color: black;">
                                <p><b><u>Message:</u></b> {{ $notification->message }}</p>
                                <small><b>Envoi prévu pour le </b>: {{ $notification->send_at }} pour <b>l'utilisateur :</b> <u>{{ $notification->user->name }}</u></small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('dashboard')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx1 = document.getElementById('professor-chart');
        if (ctx1) {
            ctx1 = ctx1.getContext('2d');
            new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: ['Actifs', 'Inactifs'],
                    datasets: [{
                        data: [{{ $professorStats['Active'] }}, {{ $professorStats['Inactive'] }}],
                        backgroundColor: ['#cb0c9f', '#36A2EB'],
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw;
                                }
                            }
                        }
                    }
                },
            });
        }

        var ctx2 = document.getElementById('course-chart');
        if (ctx2) {
            ctx2 = ctx2.getContext('2d');
            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: ['Réalisés', 'En attente', 'Ratés'],
                    datasets: [{
                        data: [{{ $courseStats['Completed'] }}, {{ $courseStats['Pending'] }}, {{ $courseStats['Missed'] }}],
                        backgroundColor: ['#36A2EB', '#cb0c9f', '#d8dee9'],
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        }

        var ctx3 = document.getElementById('institution-chart');
        if (ctx3) {
            ctx3 = ctx3.getContext('2d');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($institutionStats->toArray())) !!},
                    datasets: [{
                        label: 'Nombre d\'événements',
                        data: {!! json_encode(array_values($institutionStats->toArray())) !!},
                        backgroundColor: '#cb0c9f',
                    }],
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        } else {
            console.error('Élément avec l\'ID "institution-chart" non trouvé.');
        }
    });
</script>
@endpush

