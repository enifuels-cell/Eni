<div x-data="faqChatbot()" x-init="init()" class="fixed bottom-4 right-4 z-40">
    <div x-show="open" x-transition class="w-80 md:w-96 bg-eni-dark border border-white/10 rounded-xl shadow-2xl flex flex-col overflow-hidden">
        <div class="bg-eni-yellow text-eni-dark px-4 py-3 flex items-center justify-between">
            <h3 class="font-bold text-sm">ENI Assistant (FAQs)</h3>
            <button @click="open=false" class="text-eni-dark/70 hover:text-eni-dark" aria-label="Close FAQ widget">&times;</button>
        </div>
        <div class="p-3 border-b border-white/10 bg-black/20">
            <input x-model="query" @input.debounce.300ms="search()" type="text" placeholder="Ask about investments..." class="w-full text-sm px-3 py-2 rounded-md bg-white/10 border border-white/20 focus:outline-none focus:ring focus:ring-eni-yellow/40 text-white placeholder-white/40">
            <p class="mt-1 text-[11px] text-white/40">Project-only knowledge. No financial advice.</p>
        </div>
        <div class="flex-1 overflow-y-auto space-y-3 p-3" x-ref="results" aria-live="polite">
            <template x-if="loading">
                <div class="text-xs text-white/50">Loading...</div>
            </template>
            <template x-if="!loading && faqs.length===0">
                <div class="text-xs text-white/50">No matches. Try: "interest", "referral", or "receipt".</div>
            </template>
            <template x-for="f in faqs" :key="f.id">
                <div class="bg-white/5 border border-white/10 rounded-lg p-3">
                    <p class="text-eni-yellow text-xs font-semibold tracking-wide" x-text="formatCategory(f.category)"></p>
                    <p class="text-white font-semibold text-sm mt-1" x-text="f.question"></p>
                    <p class="text-white/70 text-xs mt-2 leading-relaxed" x-text="f.answer"></p>
                </div>
            </template>
        </div>
        <div class="px-3 py-2 bg-black/30 border-t border-white/10 text-[10px] text-white/40 flex justify-between items-center">
            <span>&#9432; Rate limited server-side</span>
            <button @click="refresh()" class="text-eni-yellow hover:underline">Refresh</button>
        </div>
    </div>
    <button x-show="!open" @click="open=true; focusSearch()" class="rounded-full w-14 h-14 bg-eni-yellow text-eni-dark font-bold shadow-lg flex items-center justify-center hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eni-yellow" aria-label="Open FAQ chatbot">?
    </button>
</div>

<script>
    function faqChatbot(){
        return {
            open:false, query:'', faqs:[], loading:false, endpoint:'{{ route('faqs.index') }}',
            init(){ this.fetchFaqs(); },
            fetchFaqs(){ this.loading=true; fetch(this.endpoint + (this.query? ('?q='+encodeURIComponent(this.query)) : ''))
                .then(r=>r.json())
                .then(j=>{ this.faqs=j.data||[]; })
                .catch(()=>{})
                .finally(()=>{ this.loading=false; }); },
            search(){ this.fetchFaqs(); },
            refresh(){ this.fetchFaqs(); },
            formatCategory(c){ return (c||'general').replace(/_/g,' ').replace(/\b\w/g,m=>m.toUpperCase()); },
            focusSearch(){ setTimeout(()=>{ const el=document.querySelector('[x-data="faqChatbot()"] input'); if(el) el.focus(); }, 50); }
        }
    }
</script>
