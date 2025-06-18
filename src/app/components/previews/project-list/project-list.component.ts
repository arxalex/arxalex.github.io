import {Component, OnInit} from '@angular/core';
import {ProjectComponent} from '../project/project.component';
import {ProjectModel} from '../project/project.model';
import {HttpClient} from '@angular/common/http';

@Component({
  selector: 'app-project-list',
  imports: [ProjectComponent],
  templateUrl: './project-list.component.html',
  styleUrl: './project-list.component.scss'
})
export class ProjectListComponent implements OnInit {
  projects: ProjectModel[] = [];

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.http.get<{projects: ProjectModel[]}>('previews/projects.json')
      .subscribe(data => this.projects = data.projects);
  }
}
