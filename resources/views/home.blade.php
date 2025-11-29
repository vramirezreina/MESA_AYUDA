@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Panel</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">                          
                        <div class="row">

                            {{-- Usuarios --}}
                            @role('Administrador|Super_Administrador')
                            <div class="col-md-4 col-xl-4">
                                <div class="card order-card" style="background: rgb(198, 210, 25)">
                                    <div class="card-block">
                                        <h5>Usuarios</h5>
                                        @php
                                            $cant_usuarios = \App\Models\User::count();
                                        @endphp
                                        <h2 class="text-right"><i class="fa fa-users f-left"></i><span>{{$cant_usuarios}}</span></h2>
                                        <p class="m-b-0 text-right"><a href="/usuarios" class="text-white">Ver más</a></p>
                                    </div>                                            
                                </div>                                    
                            </div>

                            {{-- Roles --}}
                            <div class="col-md-4 col-xl-4">
                                <div class="card order-card" style="background: rgb(0, 112, 128)">
                                    <div class="card-block">
                                        <h5>Roles</h5>
                                        @php
                                            $cant_roles = \Spatie\Permission\Models\Role::count();
                                        @endphp
                                        <h2 class="text-right"><i class="fa fa-user-lock f-left"></i><span>{{$cant_roles}}</span></h2>
                                        <p class="m-b-0 text-right"><a href="/roles" class="text-white">Ver más</a></p>
                                    </div>
                                </div>
                            </div>                                                                

                            {{-- Auditorías --}}
                            <div class="col-md-4 col-xl-4">
                                <div class="card order-card" style="background: rgb(238, 173, 26)">
                                    <div class="card-block">
                                        <h5>Auditorías Usuarios</h5>
                                        @php
                                            $cant_audit = \App\Models\Audit::count();
                                        @endphp
                                        <h2 class="text-right"><i class="fa-solid fa-clipboard-list f-left"></i><span>{{$cant_audit}}</span></h2>
                                        <p class="m-b-0 text-right"><a href="/audits" class="text-white">Ver más</a></p>
                                    </div>
                                </div>
                            </div>
                            @endrole

                            {{-- Tickets asignados o creados por el usuario --}}
                                <div class="col-md-4 col-xl-4">
                                    <div class="card order-card" style="background: rgb(0, 112, 128)">
                                        <div class="card-block">
                                            <h5>Mis Tickets</h5>
                                            @php
                                                use App\Models\Ticket;

                                                $user = auth()->user();

                                                if ($user->hasRole('Super_Administrador')) {
                                                    // Super Admin ve todos los tickets
                                                    $mis_tickets = Ticket::count();
                                                } else {
                                                    // Otros usuarios ven los tickets que crearon o les asignaron
                                                    $mis_tickets = Ticket::where(function ($query) use ($user) {
                                                        $query->where('usuario_creador_id', $user->id)
                                                            ->orWhere('usuario_asignado_id', $user->id);
                                                    })->count();
                                                }
                                            @endphp
                                            <h2 class="text-right">
                                                <i class="fa fa-ticket f-left"></i>
                                                <span>{{ $mis_tickets }}</span>
                                            </h2>
                                            <p class="m-b-0 text-right">
                                                <a href="{{ route('tickets.index') }}" class="text-white">Ver más</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>

            @role('Administrador|Super_Administrador')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">                          
                            <div class="row mt-5 justify-content-center">
                                <div class="col-md-4">
                                    <canvas id="pieChart"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <canvas id="lineChart"></canvas>
                                </div>
                            </div>   
                        </div>
                    </div>                     
                </div>
            </div>
            @endrole

    </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.font.family = 'Arial';

    // Gráfico de pastel - Usuarios por rol
    const pieCanvas = document.getElementById('pieChart');
    if (pieCanvas) {
        const pieCtx = pieCanvas.getContext('2d');
        const pieData = {!! $rolesData !!};
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(pieData),
                datasets: [{
                    data: Object.values(pieData),
                    backgroundColor: ['#f87979', '#36A2EB', '#FFCE56', '#4CAF50']
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value}`;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Gráfico de líneas - Tickets por día
    const lineCanvas = document.getElementById('lineChart');
    if (lineCanvas) {
        const lineCtx = lineCanvas.getContext('2d');
        const ticketsLabels = {!! $ticketsLabels ?? '[]' !!};
        const ticketsData = {!! $ticketsData ?? '[]' !!};
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ticketsLabels,
                datasets: [{
                    label: 'Tickets por día',
                    data: ticketsData,
                    borderColor: '#42A5F5',
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Gráfico de radar - Actividad por módulo
    const radarCanvas = document.getElementById('radarChart');
    if (radarCanvas) {
        const radarCtx = radarCanvas.getContext('2d');
        const actividad = {!! $actividadModulos !!};
        new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: Object.keys(actividad),
                datasets: [{
                    label: 'Interacción por módulo',
                    data: Object.values(actividad),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)'
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>


@endsection


