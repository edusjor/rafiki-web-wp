# CLAUDE.md

## Uso de subagentes

Prioriza el ahorro de uso y contexto.

- NO uses subagentes para tareas de diseño visual, CSS, estilos, responsive, cambios de texto, mover componentes o modificaciones pequeñas y localizadas.
- NO uses subagentes para lógica sencilla cuando ya se conoce el archivo o la función que debe modificarse.
- Haz esas tareas directamente en el agente principal.

- PUEDES usar 1 subagente cuando la tarea requiera investigación amplia antes de implementar, por ejemplo:
  - bugs cuya causa no está localizada;
  - entender lógica compleja distribuida entre varios archivos;
  - investigar flujos completos de frontend, backend y base de datos;
  - buscar todas las partes afectadas por un cambio;
  - analizar muchos logs, archivos o pruebas.

- En general, usa el subagente para INVESTIGAR y devuelve sus conclusiones al agente principal.
- La implementación final debe hacerla el agente principal cuando sea razonable.
- Usa como máximo 1 subagente por tarea.
- Solo usa 2 subagentes si el problema es realmente complejo y existen hipótesis claramente diferentes que conviene investigar en paralelo.
- No uses múltiples agentes simplemente para acelerar una tarea.
- Si puedes resolver la tarea directamente sin una investigación extensa, NO uses subagentes.

Antes de iniciar un subagente, evalúa si realmente es necesario.
Por defecto, NO uses subagentes.
Úsalos solamente cuando aislar una investigación extensa reduzca significativamente el contexto del agente principal.
