<div style="font-size: 22px;">
    <strong>
        <a wire:click.prevent="setStatus('All')" style="cursor:pointer; margin-right:10px;
            @if($status == 'All') color:blue; text-decoration: underline; @endif">All ({{ $statusCount['all_statuses'] }})</a>
    </strong>
    <strong>
        <a wire:click.prevent="setStatus('Open')" style="cursor:pointer; margin-right:10px;
            @if($status == 'Open') color:blue; text-decoration: underline; @endif">Open ({{ $statusCount['open'] }})</a>
    </strong>
    <strong>
        <a wire:click.prevent="setStatus('Considering')" style="cursor:pointer; margin-right:10px;
            @if($status == 'Considering') color:blue; text-decoration: underline; @endif">Considering ({{ $statusCount['considering'] }})</a>
    </strong>
    <strong>
        <a wire:click.prevent="setStatus('In Progress')" style="cursor:pointer; margin-right:10px;
            @if($status == 'In Progress') color:blue; text-decoration: underline; @endif">In Progress ({{ $statusCount['in_progress'] }})</a>
    </strong>
    <strong>
        <a wire:click.prevent="setStatus('Implemented')" style="cursor:pointer; margin-right:10px;
            @if($status == 'Implemented') color:blue; text-decoration: underline; @endif">Implemented ({{ $statusCount['implemented'] }})</a>
    </strong>
    <strong>
        <a wire:click.prevent="setStatus('Closed')" style="cursor:pointer; margin-right:10px;
            @if($status == 'Closed') color:blue; text-decoration: underline; @endif">Closed ({{ $statusCount['closed'] }})</a>
    </strong>
</div>
