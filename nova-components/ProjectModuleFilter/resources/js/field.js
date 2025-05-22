import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-project-module-filter', IndexField)
  app.component('detail-project-module-filter', DetailField)
  app.component('form-project-module-filter', FormField)
})
