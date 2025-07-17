<div
    x-cloak
    x-data="{ isOpen: true }"
    x-show="isOpen"
    style="position: absolute; display: flex; background-color: gray; bottom: 10px; right: 10px; margin: auto; height: 60px; padding: 20px; min-width: 240px; border-radius: 8px;">
    <strong style="flex-grow: 1;">Success message here</strong>
    <em style="cursor: pointer;" @click="isOpen = false">Close</em>
</div>
