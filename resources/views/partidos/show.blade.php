@extends('layouts.app')
@section('title', $partido->equipoLocal->nombre . ' vs ' . $partido->equipoVisitante->nombre)

@section('content')
<style>
    .show-wrap       { max-width: 680px; margin: 0 auto; }
    .match-card      { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 32px 24px; margin-bottom: 20px; }
    .match-meta      { font-size: .75rem; color: #64748b; margin-bottom: 20px; }
    .match-teams     { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
    .match-team      { flex: 1; text-align: center; }
    .match-team-name { font-size: 1.3rem; font-weight: 700; color: #fff; word-break: break-word; }
    .match-team-role { font-size: .8rem; color: #64748b; margin-top: 4px; }
    .match-center    { text-align: center; min-width: 100px; }
    .match-score     { font-size: 2.5rem; font-weight: 900; color: #fff; }
    .match-vs        { font-size: 1.2rem; font-weight: 700; color: #475569; }
    .match-date      { font-size: .85rem; color: #94a3b8; margin-top: 6px; }

    .bet-card        { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 28px 24px; }
    .bet-card h2     { font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 20px 0; }

    .cuotas-grid     { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 24px; }

    .ganancia-box    { background: #0f172a; border: 1px solid #334155; border-radius: 8px;
                       padding: 14px 18px; margin-bottom: 20px;
                       display: flex; justify-content: space-between; align-items: center; }
    .ganancia-label  { color: #94a3b8; font-size: .9rem; }
    .ganancia-valor  { font-size: 1.6rem; font-weight: 700; color: #4ade80; }

    .saldo-hint      { font-size: .78rem; color: #64748b; margin-top: 6px; }
    .saldo-hint strong { color: #4ade80; }

    .btn-apostar     { width: 100%; background: #16a34a; color: #fff; border: none; border-radius: 8px;
                       padding: 12px; font-size: 1rem; font-weight: 700; cursor: pointer; }
    .btn-apostar:hover    { background: #15803d; }
    .btn-apostar:disabled { background: #334155; color: #64748b; cursor: not-allowed; }

    .guest-box       { text-align: center; padding: 20px 0; }
    .guest-box p     { color: #94a3b8; margin-bottom: 14px; }

    .back-link       { color: #64748b; text-decoration: none; font-size: .875rem;
                       display: inline-block; margin-bottom: 16px; }
    .back-link:hover { color: #fff; }

    .finalizado-box  { background: #1e293b; border: 1px solid #334155; border-radius: 12px;
                       padding: 24px; text-align: center; color: #94a3b8; }
</style>

<div class="show-wrap">
    <a href="{{ route('partidos.index') }}" class="back-link">← Volver a partidos</a>

    {{-- Cabecera del partido --}}
    <div class="match-card">
        <div class="match-meta">{{ $partido->liga->nombre }} · {{ $partido->jornada }}</div>

        <div class="match-teams">
            <div class="match-team">
                <div class="match-team-name">{{ $partido->equipoLocal->nombre }}</div>
                <div class="match-team-role">Local</div>
            </div>

            <div class="match-center">
                @if($partido->estado === 'finalizado')
                    <div class="match-score">{{ $partido->goles_local }} - {{ $partido->goles_visitante }}</div>
                    <span class="badge badge-finalizado">Finalizado</span>
                @else
                    <div class="match-vs">vs</div>
                    <div class="match-date">{{ $partido->fecha->format('d/m/Y H:i') }}</div>
                @endif
            </div>

            <div class="match-team">
                <div class="match-team-name">{{ $partido->equipoVisitante->nombre }}</div>
                <div class="match-team-role">Visitante</div>
            </div>
        </div>
    </div>

    {{-- Formulario de apuesta --}}
    @if($partido->estado === 'pendiente' && $partido->cuota)
    <div class="bet-card">
        <h2>Realizar apuesta</h2>

        @guest
            <div class="guest-box">
                <p>Debes iniciar sesión para apostar.</p>
                <a href="{{ route('login') }}" class="btn-primary">Iniciar sesión</a>
            </div>
        @else
        <form method="POST" action="{{ route('apuestas.store', $partido) }}" id="formApuesta">
            @csrf
            <input type="hidden" name="tipo" id="tipoInput" required>

            <div class="cuotas-grid">
                <div class="cuota-box" onclick="seleccionarTipo('local', this)">
                    <div class="valor">{{ $partido->cuota->cuota_local }}</div>
                    <div class="label">1 · {{ $partido->equipoLocal->nombre }}</div>
                </div>
                <div class="cuota-box" onclick="seleccionarTipo('empate', this)">
                    <div class="valor">{{ $partido->cuota->cuota_empate }}</div>
                    <div class="label">X · Empate</div>
                </div>
                <div class="cuota-box" onclick="seleccionarTipo('visitante', this)">
                    <div class="valor">{{ $partido->cuota->cuota_visitante }}</div>
                    <div class="label">2 · {{ $partido->equipoVisitante->nombre }}</div>
                </div>
            </div>

            <div style="margin-bottom:16px">
                <label>Cantidad a apostar (€)</label>
                <input type="number" name="cantidad" id="cantidad"
                       min="1" max="{{ auth()->user()->saldo }}"
                       step="0.01" placeholder="Ej: 50"
                       oninput="calcularGanancia()">
                <div class="saldo-hint">
                    Saldo disponible: <strong>{{ number_format(auth()->user()->saldo, 2, ',', '.') }} €</strong>
                </div>
            </div>

            <div class="ganancia-box">
                <span class="ganancia-label">Ganancia potencial:</span>
                <span class="ganancia-valor" id="ganancia">— €</span>
            </div>

            <button type="submit" id="btnApostar" class="btn-apostar" disabled>
                Confirmar apuesta
            </button>
        </form>
        @endguest
    </div>

    @elseif($partido->estado === 'finalizado')
    <div class="finalizado-box">Este partido ya ha finalizado.</div>
    @endif
</div>

<script>
let cuotaSeleccionada = 0;
const cuotas = {
    local:     {{ $partido->cuota?->cuota_local ?? 0 }},
    empate:    {{ $partido->cuota?->cuota_empate ?? 0 }},
    visitante: {{ $partido->cuota?->cuota_visitante ?? 0 }},
};

function seleccionarTipo(tipo, el) {
    document.querySelectorAll('.cuota-box').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('tipoInput').value = tipo;
    cuotaSeleccionada = cuotas[tipo];
    calcularGanancia();
}

function calcularGanancia() {
    const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
    const btn = document.getElementById('btnApostar');
    if (cantidad > 0 && cuotaSeleccionada > 0) {
        document.getElementById('ganancia').textContent = (cantidad * cuotaSeleccionada).toFixed(2) + ' €';
        btn.disabled = false;
    } else {
        document.getElementById('ganancia').textContent = '— €';
        btn.disabled = true;
    }
}
</script>
@endsection
