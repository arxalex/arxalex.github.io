import {Component, Input} from '@angular/core';
import {ProjectModel} from './project.model';

@Component({
  selector: 'app-project',
  imports: [],
  templateUrl: './project.component.html',
  styleUrl: './project.component.scss'
})
export class ProjectComponent {
  @Input() project!: ProjectModel;
}
